<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Validator;
use Lang;

use JWTAuth;
use JWTAuthException;

use App\Enums\ActionStatus;
use App\Traits\APIResponder;
use App\Traits\User\UserVersion;

use App\Models\User\User;

class APIAuthController extends Controller
{
    use UserVersion,APIResponder;

    protected $guard = "api";
    private $url = "/api/auths/";

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Register User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $validator = Validator::make($request->json()->all(), [$this->postCreateUserValidation(),$this->createUserMessages()]);
        if($validator->fails()){
            $response = $validator->messages()->all();
            return $this->errorResponse($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $newUser = User::create([
            'user_first_name' => $request->json()->get('name'),
            'user_surname' => $request->json()->get('surname'),
            'email' => $request->json()->get('email'),
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make($request->json()->get('password')),
        ]);

        $arr = array();
        $arr['dateCreated'] = $newUser->created_at;
        $arr['dateModified'] = $newUser->updated_at;
        $arr['id'] = $newUser->id;
        $arr['firstName'] = $newUser->user_first_name;
        $arr['surname'] = $newUser->user_surname;
        $arr['email'] = $newUser->email;
        $arr['userStatus'] = $newUser->user_status;
        $arr['isActive'] = ($newUser->isActive == "1") ? true : false;
        $arr['url'] = $this->url.$newUser->id;
        $arr['status'] = ActionStatus::Success;
        $arr['message'] =  __('global.api-user.messages.create.success');
        $response = [
            'users'=> $arr
        ];

        return $this->successProcessedResponse(1,$response,Response::HTTP_CREATED);
    }

    /**
     * Get the authenticated User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        $rules = [
            'email' => 'required|filled|string|email',
            'password' => 'required|filled|string'
        ];
        $validator = Validator::make($credentials,$rules);
        if ($validator->fails()) {
            $response = $validator->messages()->all();
            return $this->errorResponse($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $token = null;
        try {
            $user = User::where('email',$credentials['email'])->first();
            if($user){
                if (! $token = auth($this->guard)->attempt($validator->validated())) {
                    return $this->errorResponse(Lang::get('global.app_required_credentials'),Response::HTTP_UNAUTHORIZED);
                }
        
                $token = JWTAuth::fromUser($user);
                $user->api_token = $token;
                $user->save();

                return $this->createNewToken($token);
            }else{
                return $this->errorResponse(Lang::get('global.app_required_credentials'),Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTAuthException $e) {
            return $this->errorResponse(Lang::get('global.app_token_failed'),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(){
        $user = User::where('email',auth()->user()->email)->first();
        $response = [           
            'status' => ActionStatus::Error,
            'message' => __('global.api-user.messages.view.error')
        ];
        if($user){
            $isActive = ($user->isActive == "1") ? true : false;
            $arr = array();
            $arr['dateCreated'] = $user->created_at;
            $arr['dateModified'] = $user->updated_at;
            $arr['id'] = $user->id;
            $arr['firstName'] = $user->user_first_name;
            $arr['surname'] = $user->user_surname;
            $arr['email'] = $user->email;
            $arr['userStatus'] = $user->user_status;
            $arr['isActive'] = $isActive;   
            $arr['url'] = $this->url.$user->id;                
            $arr['status'] = ActionStatus::Success;
            $arr['message'] = __('global.api-user.messages.view.success');
            $response = $arr;
        }

        return $this->successResponse(['profile'=> $response],Response::HTTP_OK);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        auth()->logout();
        
        return $this->successResponse(['message'=> Lang::get('global.logout')],Response::HTTP_OK);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(){
        return $this->createNewToken(auth($this->guard)->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        $arr = [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL()
        ];
        return $this->successResponse($arr,Response::HTTP_ACCEPTED);
    }

    public function generate(Request $request){
        $password = trim($request->route('password'));
        if($password === "" || $password === null){
            return $this->errorResponse(Lang::get('global.app_password_required'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->successResponse(['password'=>Hash::make($request->route('password'))],Response::HTTP_ACCEPTED);
    }
}
