<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use Carbon\Carbon;
use Faker\Generator as Faker;
use Faker\Factory as Custom;
use Validator;

use App\Enums\ActionStatus;
use App\Enums\PostStatus;
use App\Models\User\User;
use App\Models\Post\Post;

use App\Traits\Post\PostVersion;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;


class PostTest extends TestCase
{
    private $version = "v1/";
    protected $apiUserEmail = "testthe23232o@gmail.com";
    protected $apiUserPassword = "123456";
    
    /**
     * Login API User
     *
     * @return string
     */
    private function getAPIUserLogin(){

        $payload = [
            'email' => $this->apiUserEmail,
            'password' => $this->apiUserPassword
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

    public function test_Create_Post_Form_Validation_Passes(){
        $faker = Custom::create();

        $user_id = User::where('email',$this->apiUserEmail)->pluck('id')->first();
        $title = $faker->word();
        $num = $faker->randomElement(array('0','1','2'));
        $arr = $faker->sentences();
        if($num == 0)$arr = $faker->paragraphs();
        if($num == 1)$arr = $faker->paragraphs(1);
        if($num == 2)$arr = $faker->paragraphs(2);
        $paragraphs = null;
        for ($i=0; $i < count($arr); $i++) { 
            $paragraphs .= $arr[$i];
        }

        $post = [
            'title' => $title,
            'content' => $paragraphs
        ];

        $request = new StorePostRequest();
        $rules = $request->rules();
        $validator = Validator::make($post, $rules);
        $passes = $validator->passes();

        if ($validator->fails()) {
            $arrError =  $validator->messages()->all();
            $message = formatValidationErrors($arrError);
            $this->fail($message);
        }
        $this->assertEquals(true, $passes);
    }

    /**
     * Create Post successfully
     *
     * @return void
     */
    public function test_Can_Create_Post_Successfully()
    {
        $faker = Custom::create();

        //Login
        $token = $this->getAPIUserLogin();
        if($token){
            $headers = [       
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];

            $user_id = User::where('email',$this->apiUserEmail)->pluck('id')->first();
            $title = $faker->word();
            $num = $faker->randomElement(array('0','1','2'));
            $arr = $faker->sentences();
            if($num == 0)$arr = $faker->paragraphs();
            if($num == 1)$arr = $faker->paragraphs(1);
            if($num == 2)$arr = $faker->paragraphs(2);
            $paragraphs = null;
            for ($i=0; $i < count($arr); $i++) { 
                $paragraphs .= $arr[$i];
            }

            $payload = [
                'title' => $title,
                'content' => $paragraphs
            ];
 
            $methodURL = 'api/'.$this->version.'posts/create';
            $response = $this->json('POST',$methodURL,$payload,$headers);
            $data = $response->getData();
            $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
            $code = (isset($data->code)) ? $data->code : null;
            $message = (isset($data->message)) ? $data->message : null;
            if($resultStatus == "error"){
                $error = $message;
                if(isset($message->message))$error = $message->message;
                $this->fail($error);
            }

            if($message){
                $posts = $message->posts;
                $status = strtolower($posts->status);
                if($status == "error"){
                    $error = 'Tile: '.$title.' \r\n ';
                    $error .= 'Content: '.$paragraphs.' \r\n ';
                    $error .= 'Message: '.$posts->message;
                    $this->fail($error);
                }

                $id = (isset($posts->id)) ? $posts->id : null;
                $dateCreated = (isset($posts->dateCreated)) ? $posts->dateCreated : null;
                $dateModified = (isset($posts->dateModified)) ? $posts->dateModified : null;
                $response->assertStatus(Response::HTTP_CREATED);
                $response->assertJsonStructure([
                    'result',
                    'message' => [
                        'posts' => [
                            'dateCreated','dateModified','id','title','content',
                            'postStatus','isActive','status','message'      
                        ],
                    ],
                    'code',
                    'recordCount'
                ]);
            }
        }
    }

    /**
     * View Post successfully
     *
     * @return void
     */
    public function test_Can_View_Post_Successfully()
    {
        //Login
        $token = $this->getAPIUserLogin();
        if($token){
            $headers = [       
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];

            $post = Post::latest('id')->first();
            $id = $post->id;
            $payload = [];
            $methodURL = 'api/'.$this->version.'posts/'.$id;
            $response = $this->json('GET',$methodURL,$payload,$headers);
            $data = $response->getData();
            $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
            $code = (isset($data->code)) ? $data->code : null;
            $message = (isset($data->message)) ? $data->message : null;
            if($resultStatus == "error"){
                $error = $message;
                if(isset($message->message))$error = $message->message;
                $this->fail($error);
            }
            $response->assertStatus(Response::HTTP_FOUND);
            $response->assertJsonStructure([
                'result',
                'message' => [
                    'posts' => [
                        'dateCreated','dateModified','id','title','content',
                        'postStatus','isActive','status','message'  
                    ]
                ],
                'code',
                'recordCount'
            ]);
        }
    }

    /**
     * Update Post successfully
     *
     * @return void
     */
    public function test_Can_Update_Post_Successfully()
    {
        //Login
        $token = $this->getAPIUserLogin();
        if($token){
            $headers = [       
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];

            $post = Post::latest('id')->first();
            $post_id = $post->id;
            $faker = Custom::create();
            $user_id = User::where('email',$this->apiUserEmail)->pluck('id')->first();
            $title = $faker->word();
            $num = $faker->randomElement(array('0','1','2'));
            $arr = $faker->sentences();
            if($num == 0)$arr = $faker->paragraphs();
            if($num == 1)$arr = $faker->paragraphs(1);
            if($num == 2)$arr = $faker->paragraphs(2);
            $paragraphs = null;
            for ($i=0; $i < count($arr); $i++) { 
                $paragraphs .= $arr[$i];
            }

            $payload = [
                'title' => $title,
                'content' => $paragraphs
            ];

            $methodURL = 'api/'.$this->version.'posts/'.$post_id;
            $response = $this->json('PUT',$methodURL,$payload,$headers);
            $data = $response->getData();
            $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
            $code = (isset($data->code)) ? $data->code : null;
            $message = (isset($data->message)) ? $data->message : null;
            if($resultStatus == "error"){
                $error = $message;
                if(isset($message->message))$error = $message->message;
                $this->fail($error);
            }

            if($message){
                $posts = $message->posts;
                $status = strtolower($posts->status);
                if($status == "error"){
                    $error = 'Tile: '.$title.' \r\n ';
                    $error .= 'Content: '.$paragraphs.' \r\n ';
                    $error .= 'Message: '.$posts->message;
                    $this->fail($error);
                }
                $posts = $message->posts;
                $dateModified = (isset($posts->dateModified)) ? $posts->dateModified : null;
                $postStatus = (isset($posts->postStatus)) ? $posts->postStatus : null;
                $isActive = (isset($posts->isActive)) ? $posts->isActive : null;
                $isActive = ($isActive == "1") ? true : false;
                $response->assertStatus(Response::HTTP_OK);
                $response->assertJsonStructure([
                    'result',
                    'message' => [
                        'posts' => [
                            'dateCreated','dateModified','id','title','content',
                            'postStatus','isActive','status','message'      
                        ],
                    ],
                    'code',
                    'recordCount'
                ]);
            }
        }
    }

    /**
     * Delete Post successfully
     *
     * @return void
     */
    public function test_Can_Delete_Post_Successfully()
    {
        //Login
        $token = $this->getAPIUserLogin();
        if($token){
            $headers = [       
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];

            $post = Post::latest('id')->first();
            $post_id = $post->id;
            $payload = []; 
            $methodURL = 'api/'.$this->version.'posts/'.$post_id;
            $response = $this->json('DELETE',$methodURL,$payload,$headers);
            $data = $response->getData();
            $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
            $code = (isset($data->code)) ? $data->code : null;
            $message = (isset($data->message)) ? $data->message : null;
            if($resultStatus == "error"){
                $error = $message;
                if(isset($message->message))$error = $message->message;
                $this->fail($error);
            }
            if($message){
                $posts = $message->posts;
                $status = strtolower($posts->status);
                if($status == "error"){
                    $error = 'Post ID: '.$post_id.' \r\n ';
                    $error .= 'Message: '.$posts->message;
                    $this->fail($error);
                }

                $response->assertStatus(Response::HTTP_OK);
                if($post){
                    $posts = $message->posts;
                    $dateDeleted = (isset($posts->dateDeleted)) ? $posts->dateDeleted : null;
                    $response->assertStatus(Response::HTTP_OK);
                    $response->assertJsonStructure([
                        'result',
                        'message' => [
                            'posts' => [
                                'dateDeleted','id','postStatus','isActive','status','message'  
                            ]
                        ],
                        'code',
                        'recordCount'
                    ]);
                }else{
                    $deletePost = Post::withTrashed()->where('id',$post_id)->first(); 
                    $dateDeleted = (isset($deletePost->deleted_at)) ? $deletePost->deleted_at : null;
                    $response->assertStatus(Response::HTTP_OK);
                    $response->assertJsonStructure([
                        'result',
                        'message' => [
                            'posts' => [
                                'dateDeleted','id','postStatus','isActive','status','message'  
                            ]
                        ],
                        'code',
                        'recordCount'
                    ]);
                }
            }
            
        }
    }

    /**
     * List Post successfully
     *
     * @return void
     */
    public function test_Can_List_Post_Successfully()
    {
        //Login
        $token = $this->getAPIUserLogin();
        if($token){
            $headers = [       
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];
            
            $payload = [];
            $methodURL = 'api/'.$this->version.'posts';
            $response = $this->json('GET',$methodURL,$payload,$headers);
            $data = $response->getData();
            //dd($data);
            $resultStatus = (isset($data->result)) ? strtolower($data->result) : "error";
            $code = (isset($data->code)) ? $data->code : null;
            $message = (isset($data->message)) ? $data->message : null;
            if($resultStatus == "error"){
                $error = $message;
                if(isset($message->message))$error = $message->message;
                $this->fail($error);
            }
            $response->assertStatus(Response::HTTP_OK);
            $response->assertJsonStructure([
                'result',
                'message' => [
                    'posts' => [ 
                        '*' => [
                            'created_at','updated_at','id','post_title','post_content',
                            'post_status','isActive',
                        ]
                    ],
                ],
                'code',
                'recordCount'

            ]);
        }
    }
}
