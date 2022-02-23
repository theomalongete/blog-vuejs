<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Post\Post;
use App\Traits\Post\PostVersion;

class UpdatePostRequest extends FormRequest
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

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->putUpdatePostValidation();
        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $messages = $this->updatePostMessages();
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
     * Update Post
     *
     * @return boolean
     */
    public function persist($id)
    {
        $valid = DB::transaction(function () use ($id){ 
            //dd(request()->all());
            $title = (trim(request('title')) != null) ? trim(request('title')) : null;
            $content = (trim(request('content')) != null) ? trim(request('content')) : null;
            $post = Post::where('id',$id)->where('user_id',Auth::user()->id)->first();

            if($post){
                $post->post_title = $title;
                $post->post_content = $content;
                $result = $post->save();

                if($result)return true;
            }

            return false;
        });

        return $valid;
    }
}
