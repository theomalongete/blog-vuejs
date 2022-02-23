<?php

namespace App\Http\Controllers\API\V1\Post;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Lang;

use App\Traits\APIResponder;
use App\Enums\ActionStatus;

use App\Models\Post\Post;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{
    use APIResponder;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(){
       //$this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('id','DESC')->get();
        $response = [
            'posts'=> $posts
        ];

        return $this->successProcessedResponse($posts->count(),$response,Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Post\StorePostRequest  $form
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $form){

        if(Auth::check()){
            $response = null;
            $valid = DB::transaction(function () use($form,&$response){ 

                $result = $form->persist();
                $arr = array();
                if($result){
                    $title = (trim(request('title')) != null) ? trim(request('title')) : null;
                    $content = (trim(request('content')) != null) ? trim(request('content')) : null;
    
                    $newPost = Post::where('post_title',$title)->where('post_content',$content)->first();
                    if($newPost){
                        $arr['dateCreated'] = $newPost->created_at;
                        $arr['dateModified'] = $newPost->updated_at;
                        $arr['id'] = $newPost->id;
                        $arr['title'] = $newPost->post_title;
                        $arr['content'] = $newPost->post_content;
                        $arr['postStatus'] = $newPost->post_status;
                        $arr['isActive'] = ($newPost->isActive == "1") ? true : false;
                        $arr['status'] = ActionStatus::Success;
                        $arr['message'] =  __('global.post.messages.create.success');
                        $response = [
                            'posts'=> $arr
                        ];

                        return true;
                    }
                }else{
                    $arr['title'] = $title;
                    $arr['content'] = $content;
                    $arr['status'] = ActionStatus::Error;
                    $arr['message'] =  __('global.post.messages.create.error');
                    $response = [
                        'posts'=> $arr
                    ];
                }

                return false;
            });

            return $this->successProcessedResponse(1,$response,Response::HTTP_CREATED);
        }

        return $this->errorResponse(__('global.app_unauthorized_user'),Response::HTTP_FORBIDDEN);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        $arr = array();
        if($post){
            $arr['dateCreated'] = $post->created_at;
            $arr['dateModified'] = $post->updated_at;
            $arr['id'] = $post->id;
            $arr['title'] = $post->post_title;
            $arr['content'] = $post->post_content;
            $arr['postStatus'] = $post->post_status;
            $arr['isActive'] = ($post->isActive == "1") ? true : false;
            $arr['status'] = ActionStatus::Success;
            $arr['message'] =  __('global.post.messages.view.success'); 
        }else{
            $arr['id'] = $post->id;
            $arr['status'] = ActionStatus::Error;
            $arr['message'] =  __('global.post.messages.view.error');
        }
        $response = [
            'posts'=> $arr
        ];

        return $this->successProcessedResponse(1,$response,Response::HTTP_FOUND);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Post\UpdatePostRequest  $form
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $form, $id)
    {
        if(Auth::check()){
            $response = null;
            $valid = DB::transaction(function () use($form,$id,&$response){ 

                $result = $form->persist($id);
                $arr = array();
           
                if($result){
                    $updatePost = Post::where('id',$id)->first();
                    if($updatePost){
                        $arr['dateCreated'] = $updatePost->created_at;
                        $arr['dateModified'] = $updatePost->updated_at;
                        $arr['id'] = $updatePost->id;
                        $arr['title'] = $updatePost->post_title;
                        $arr['content'] = $updatePost->post_content;
                        $arr['postStatus'] = $updatePost->post_status;
                        $arr['isActive'] = ($updatePost->isActive == "1") ? true : false;
                        $arr['status'] = ActionStatus::Success;
                        $arr['message'] =  __('global.post.messages.update.success');
                        $response = [
                            'posts'=> $arr
                        ];

                        return true;
                    }
                }else{
                    $title = (trim(request('title')) != null) ? trim(request('title')) : null;
                    $content = (trim(request('content')) != null) ? trim(request('content')) : null;
                    $arr['id'] = $id;
                    $arr['title'] = $title;
                    $arr['content'] = $content;
                    $arr['status'] = ActionStatus::Error;
                    $arr['message'] =  __('global.post.messages.update.error'); 
                    $response = [
                        'posts'=> $arr
                    ];
                }

                return false;
            });

            if($valid)return $this->successProcessedResponse(1,$response,Response::HTTP_OK);
            return $this->errorProcessedResponse(1,$response,Response::HTTP_NOT_FOUND);
        }

        return $this->errorResponse(__('global.app_unauthorized_user'),Response::HTTP_FORBIDDEN);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::check()){
            $response = null;
            $valid = DB::transaction(function () use($id,&$response){ 

                $updatePost = Post::where('id',$id)->first();

                $result = null;
                if (!$updatePost) {
                    //Check if Post is already deleted
                    $deletedPost = Post::withTrashed()->where('id',$id)->first();
                    if($deletedPost){
                        $arr = array();
                        $arr['dateDeleted'] = $deletedPost->deleted_at;
                        $arr['id'] = $id;
                        $arr['title'] = $deletedPost->post_title;
                        $arr['content'] = $deletedPost->post_content;
                        $arr['isActive'] = ($deletedPost->isActive == "1") ? true : false;
                        $arr['status'] = ActionStatus::Success;
                        $arr['message'] =  __('global.post.messages.delete.already');
                        $response = [
                            'posts'=> $arr
                        ];

                        return true;
                    }else{
                        $message = ['id'=>$id,'status'=>ActionStatus::Error,'message'=>__('global.post.messages.view.error')];
                        $response = [
                            'posts'=> $message
                        ];
                        return false;
                    }
                }else{
                    $updatePost->isActive = "0";
                    $updatePost->save();
                    $updatePost->delete();

                    $arr = array();
                    $arr['dateDeleted'] = $updatePost->deleted_at;
                    $arr['id'] = $id;
                    $arr['title'] = $updatePost->post_title;
                    $arr['content'] = $updatePost->post_content;
                    $arr['postStatus'] = $updatePost->post_status;
                    $arr['isActive'] = ($updatePost->isActive == "1") ? true : false;
                    $arr['status'] = ActionStatus::Success;
                    $arr['message'] = __('global.post.messages.delete.success');
                    $response = [
                        'posts'=> $arr
                    ];

                    return true;
                }
            });

            if($valid)return $this->successProcessedResponse(1,$response,Response::HTTP_OK);
            return $this->errorProcessedResponse(1, $response,Response::HTTP_NOT_FOUND);
       }

       return $this->errorResponse(__('global.app_unauthorized_user'),Response::HTTP_FORBIDDEN);
    }
}
