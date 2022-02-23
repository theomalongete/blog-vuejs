<?php

namespace App\Traits\Post;

use Illuminate\Http\Request;
use App\Exceptions\RequestValidationException;
use Symfony\Component\HttpFoundation\Response; 
use Validator;

/**
 * Traits are a mechanism for code reuse in single inheritance languages such 
 * as PHP.  A Trait is intended to reduce some limitations of single 
 * inheritance by enabling a developer to reuse sets of methods freely in 
 * several independent classes living in different class hierarchies.
 */
trait PostVersion{
    /**
     * Validation messages for Create Post
     *
     * @return array
     */
    private function createPostMessages() {
        $messages =  [
            'title.required' => 'Title is required.',
            'title.filled' => 'Title must have a value.',
            'title.string' => 'Title must be a string.',

            'content.required' => 'Content is required.',
            'content.filled' => 'Content must have a value.',
            'content.string' => 'Content must be a string.',

            'userID.required' => 'User is required.',
            'userID.filled' => 'User must have a value.',
            'userID.numeric' => 'User must be a string.',

            'comment.string' => 'CustomField must be a string.',
        ];

        return $messages;
    }

    /**
     * Validation for Create Post 
     *
     * @return array
     */
    private function postCreatePostValidation() {
        return [
            'title' => 'bail|required|filled|string|max:255',
            'content' => 'bail|required|filled|string',
            //'userID' => 'bail|required|filled|string',

            //Optional fields
            'comment' => 'nullable|string',
        ];
    }

    /**
     * Validation messages for Update Post
     *
     * @return array
     */
    private function updatePostMessages() {

        $messages =  [
            'title.required' => 'Title is required.',
            'title.filled' => 'Title must have a value.',
            'title.string' => 'Title must be a string.',

            'content.required' => 'Content is required.',
            'content.filled' => 'Content must have a value.',
            'content.string' => 'Content must be a string.',

            // 'userID.required' => 'User is required.',
            // 'userID.filled' => 'User must have a value.',
            // 'userID.numeric' => 'User must be a string.',

            'comment.string' => 'CustomField must be a string.'
        ];

        return $messages;
    }

    /**
     * Validation for Update Post 
     *
     * @return array
     */
    private function putUpdatePostValidation() {
        return [
            'title' => 'bail|required|filled|string|max:255',
            'content' => 'bail|required|filled|string',
            //'userID' => 'bail|required|filled|string',

            //Optional fields
            'comment' => 'nullable|string'
        ];
    }
}