<?php
    return [
        'api-user'=> [
            'messages' => [
                'create' => [
                    'success' => 'API User created',
                    'error' => 'API User has not been created',
                    'already' => 'API User e-mail address already exists'
                ],
                'view' => [
                    'success' => 'API User found',
                    'error' => 'API User cannot be found'
                ],
                'update' => [
                    'success' => 'API User updated',
                    'error' => 'API User cannot be updated',
                ],
                'delete' => [
                    'success' => 'API User deleted',
                    'error' => 'API User cannot be deleted',
                    'already' => 'API User already deleted'
                ],
            ]
        ],
        'post'=> [
            'messages' => [
                'create' => [
                    'success' => 'Post created',
                    'error' => 'Post has not been created',
                    'already' => 'Post already exists'
                ],
                'view' => [
                    'success' => 'Post found',
                    'error' => 'Post cannot be found'
                ],
                'update' => [
                    'success' => 'Post updated',
                    'error' => 'Post cannot be updated',
                ],
                'delete' => [
                    'success' => 'Post deleted',
                    'error' => 'Post cannot be deleted',
                    'already' => 'Post already deleted'
                ],
            ]
        ],
        'app_required_credentials' => 'Invalid email or password',
        'app_unauthorized_user' => 'User is unauthorized',
        'app_forbidden_user' => 'User permission is forbidden',
        'app_token_required' => 'Token is required',
        'app_token_expired' => 'Token has expired',
        'app_token_invalid' => 'Token is invalid',
        'app_token_failed' => 'Failed to create token',
        'app_password_required' => 'Password is required',
        'logout' => 'Successfully logged out'
    ];