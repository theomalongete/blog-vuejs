<?php

namespace App\Traits\User;

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

trait UserVersion{
    /**
     * Validation messages for Create User
     *
     * @return array
     */
    private function createUserMessages() {
        $messages =  [
            'name.required' => 'Name is required.',
            'name.filled' => 'Name must have a value.',
            'name.string' => 'Name must be a string.',

            'surname.required' => 'Surname is required.',
            'surname.filled' => 'Surname must have a value.',
            'surname.string' => 'Surname must be a string.',

            'email.required' => 'E-mail Address is required.',
            'email.filled' => 'E-mail Address must have a value.',
            'email.string' => 'E-mail Address must be a string.',
            'email.email' => 'E-mail Address must be a valid email address.',
            'email.unique' => 'E-mail Address has already been taken.'
        ];

        return $messages;
    }

    /**
     * Validation for Create User  
     *
     * @return array
     */
    private function postCreateUserValidation() {

        return [
            'name' => 'bail|required|filled|string|max:255',
            'surname' => 'bail|required|filled|string|max:255',
            'email' => 'bail|required|filled|string|email|max:255|unique:users',
            'password' => 'bail|required|filled|string|min:8|max:255'
        ];
    }
}