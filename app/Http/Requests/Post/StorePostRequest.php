<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Post\Post;
use App\Traits\Post\PostVersion;

class StorePostRequest extends FormRequest
{
    use PostVersion;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        if(Auth::check())return true;
       // return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->postCreatePostValidation();
        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $messages = $this->createPostMessages();
        return $messages;
    }


    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return Illuminate\Http\Response
     */
    public function withValidator($validator)
    {
        if ($validator->fails()) {
            $arrError =  $validator->messages()->all();
            return $arrError;
        } 
    }

    /**
     * Create Post
     *
     * @return boolean
     */
    public function persist()
    {
        $valid = DB::transaction(function () { 

            $title = (trim(request('title')) != null) ? trim(request('title')) : null;
            $content = (trim(request('content')) != null) ? trim(request('content')) : null;
            $post = Post::where('post_title',$title)->where('post_content',$content)->where('user_id',Auth::user()->id)->first();

            if(!$post){
                $post = Post::firstOrCreate([
                    'post_title' => $title,
                    'post_content' => $content,
                    'user_id' => Auth::user()->id
                ]);

                $insertedID = $post->id;
                if(!$insertedID)return false;
            }

            return true;
        });

        return $valid;
    }
}
