<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Carbon\Carbon;

use App\Enums\ActionStatus;

class APILoginTest extends TestCase
{
    protected $apiUserEmail = "testthe23232o@gmail.com";
    protected $apiUserPassword = "123456";
    /**
     * Requires Email and Password.
     *
     * @return void
     */
    public function test_Requires_Email_And_Password()
    {
        $payload = [
            'email'    => '',
            'password' => ''
        ];
        $response = $this->json('POST', 'api/auths/login',$payload);
        $data = $response->getData();
        $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
        $code = (isset($data->code)) ? $data->code : null;
        $message = (isset($data->message)) ? $data->message : null;
        if($resultStatus == "success"){
            $this->fail($message);
        }
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response ->assertJson([
            'result' => 'error',
            'message' => ["The email field is required.","The password field is required."],
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ]);
    }

    /**
     * Login API User unsuccessfully
     *
     * @return void
     */
    public function test_Can_Login_API_User_Unsuccessfully()
    {
        $payload = [
            'email'    => 'test@test.co.za',
            'password' => 'test@test.co.za',
        ];
        $response = $this->json('POST', 'api/auths/login',$payload);
        $data = $response->getData();
        $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
        $code = (isset($data->code)) ? $data->code : null;
        $message = (isset($data->message)) ? $data->message : null;
        if($resultStatus == "success"){
            $this->fail($message);
        }
        $response ->assertStatus(Response::HTTP_UNAUTHORIZED );
        $response ->assertJson([
            'result' => 'error',
            'message' => __('global.app_required_credentials'),
            'code' => Response::HTTP_UNAUTHORIZED
        ]);
    }

    /**
     * Login API Auth user successfully
     *
     * @return void
     */
    public function test_Can_Login_API_User_Auth_Successfully()
    {

        $payload = [
            'email'    => $this->apiUserEmail,
            'password' => $this->apiUserPassword,
        ];
        $response = $this->json('POST','api/auths/login', $payload);
        $data = $response->getData();
        $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
        $code = (isset($data->code)) ? $data->code : null;
        $message = (isset($data->message)) ? $data->message : null;
        if($resultStatus == "error"){
            $error = $message;
            if(isset($message->message))$error = $message->message;
            $this->fail($error);
        }
        $token = $message->token;
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson([
            'result' => 'success',
            'message' => ['token' => $token],
            'code' => Response::HTTP_ACCEPTED,
        ]);
    }

    /**
     * Login API Auth user
     *
     * @return string
     */
    private function getAPIUserLogin(){
        $payload = [
            'email'    => $this->apiUserEmail,
            'password' => $this->apiUserPassword,
        ];
        $response = $this->json('POST', 'api/auths/login',$payload);
        $data = $response->getData();
        $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
        $code = (isset($data->code)) ? $data->code : null;
        $message = (isset($data->message)) ? $data->message : null;
        if($resultStatus == "error"){
            $error = $message;
            if(isset($message->message))$error = $message->message;
            $this->fail($error);
        }
        $token = ($resultStatus == "success") ? $message->token : null;
        return $token;
    }

    /**
     * Generate Password successfully
     *
     * @return void
     */
    public function test_Can_Generate_Password_Successfully(){
        //Login
        $token = $this->getAPIUserLogin();
        if($token){
            $headers = [       
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];

            $password = "test";
            $response = $this->json('GET','api/auths/generate/'.$password,$headers);
            $data = $response->getData();
            $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
            $code = (isset($data->code)) ? $data->code : null;
            $message = (isset($data->message)) ? $data->message : null;
            if($resultStatus == "error"){
                $error = $message;
                if(isset($message->message))$error = $message->message;
                $this->fail($error);
            }
            $password = $message->password;
            $response->assertStatus(Response::HTTP_ACCEPTED);
            $response->assertJson([
                'result' => 'success',
                'message' => ['password' => $password],
                'code' => Response::HTTP_ACCEPTED,
            ]);
        }
    }

    /**
     * Refresh Token successfully
     *
     * @return void
     */
    public function test_Can_Refresh_Token_Successfully(){
        //Login
        $token = $this->getAPIUserLogin();
        if($token){
            $headers = [       
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];

            $response = $this->json('POST','api/auths/refresh',$headers);
            $data = $response->getData();
            $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
            $code = (isset($data->code)) ? $data->code : null;
            $message = (isset($data->message)) ? $data->message : null;
            if($resultStatus == "error"){
                $error = $message;
                if(isset($message->message))$error = $message->message;
                $this->fail($error);
            }
            $token = $message->token;
            $response->assertStatus(Response::HTTP_ACCEPTED);
            $response->assertJsonStructure([
                'result',
                'message' => [
                    'token',
                    'token_type',
                    'expires_in'
                ],
                'code'
            ]);
        }
    }

    /**
     * View API User profile successfully
     *
     * @return void
     */
    public function test_Can_GET_API_User_Profile_Successfully(){
        //Login
        $token = $this->getAPIUserLogin();
        if($token){
            $headers = [       
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];

            $response = $this->json('GET','api/auths/profile',$headers);
            $data = $response->getData();
            $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
            $code = (isset($data->code)) ? $data->code : null;
            $message = (isset($data->message)) ? $data->message : null;
            if($resultStatus == "error"){
                $error = $message;
                if(isset($message->message))$error = $message->message;
                $this->fail($error);
            }
            $response->assertStatus(Response::HTTP_OK);
            $response->assertJson([
                'result' => 'success',
                'message' => [
                    'profile' => [
                        'status' => ActionStatus::Success,
                        'message' => __('global.api-user.messages.view.success')
                    ]
                ],
                'code' => Response::HTTP_OK ,
            ]);
            $response->assertJsonStructure([
                'result',
                'message' => [
                    'profile' => [
                        'dateCreated',
                        'dateModified',
                        'id',
                        'firstName',
                        'surname',
                        'email',
                        'userStatus',
                        'isActive',
                        'url',
                        'status',
                        'message'
                    ]
                ],
                'code'
            ]);
        }
    }

    /**
     * Logout API AUserr successfully
     *
     * @return void
     */
    public function test_Can_Logout_API_User_Successfully(){
        //Login
        $token = $this->getAPIUserLogin();
        if($token){
            $headers = [       
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];

            $response = $this->json('POST','api/auths/logout',$headers);
            $data = $response->getData();
            $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
            $code = (isset($data->code)) ? $data->code : null;
            $message = (isset($data->message)) ? $data->message : null;
            if($resultStatus == "error"){
                $error = $message;
                if(isset($message->message))$error = $message->message;
                $this->fail($error);
            }
            $response->assertStatus(Response::HTTP_OK);
            $response->assertJson([
                'result' => 'success',
                'message' => [
                    'message' => __('global.logout')
                ],
                'code' => Response::HTTP_OK ,
            ]);
        }
    }
}
