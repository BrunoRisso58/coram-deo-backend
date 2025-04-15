<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * Validate the request data for user creation.
     *
     * @return array
     */
    public static function validateUserCreation(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Validate the request data for user update.
     *
     * @return array
     */
    public static function validateUserUpdate(): array
    {
        return [
            'name' => 'string|max:255',
            'phone' => 'string|max:15|unique:users,phone',
            'email' => 'email|unique:users,email',
            'password' => 'string|min:8',
        ];
    }
}