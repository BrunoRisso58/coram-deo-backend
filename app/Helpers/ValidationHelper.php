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

    /**
     * Validate the login data.
     *
     * @return array
     */
    public static function getLoginData(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ];
    }

    /**
     * Validate the checkEmail data.
     *
     * @return array
     */
    public static function getCheckEmailData(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    /**
     * Validate the request data for goal creation.
     *
     * @return array
     */
    public static function validateGoalCreation(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'days' => 'required|string',
            'reminder_days' => 'string',
            'reminder_time' => 'string',
        ];
    }

    /**
     * Validate the request data for goal instance creation.
     *
     * @return array
     */
    public static function validateGoalInstancesCreation(): array
    {
        return [
            'date' => 'required|date',
        ];
    }

    /**
     * Validate the request data for getting goal instances
     *
     * @return array
     */
    public static function validateGetGoalInstances(): array
    {
        return [
            'date' => 'required|date',
        ];
    }

    /**
     * Validate the request data for updating goal.
     *
     * @return array
     */
    public static function validateUpdateGoal(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'days' => 'nullable|string',
            'reminder_days' => 'nullable|string',
            'reminder_time' => 'nullable|string',
            'date' => 'required|date',
        ];
    }
}