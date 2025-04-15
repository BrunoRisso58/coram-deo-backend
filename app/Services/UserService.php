<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Repo\UserRepository;
use App\Traits\ApiResponse;

class UserService
{
    use ApiResponse;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return JsonResponse
     */
    public function signUp(array $data): JsonResponse
    {
        try {
            $user = $this->userRepository->createUser($data);
            return $this->successResponse(['user' => $user], 'User created successfully');
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return $this->errorResponse('User creation failed', 500);
        }
    }

    /**
     * get user by id
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function getUser(int $id, Request $request): JsonResponse
    {
        try {
            // $authenticatedUser = $request->user(); // TODO: get authenticated user after login is finished
            $user = $this->userRepository->getUser($id);
            
            // if ($authenticatedUser->role !== 'admin' && $authenticatedUser->email !== $user['email']) {
            //     throw new Exception('Unauthorized access');
            // }

            return $this->successResponse($user, 'User retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error retrieving user: ' . $e->getMessage());
            return $this->errorResponse('User not found', 404);
        }
    }

    /**
     * update user
     *
     * @param int $id
     * @param array $data
     * @return JsonResponse
     */
    public function updateUser(int $id, array $data): JsonResponse
    {
        try {
            $user = $this->userRepository->updateUser($id, $data);
            return $this->successResponse($user, 'User updated successfully');
        } catch (Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return $this->errorResponse('Failed to update user', 404);
        }
    }

    /**
     * delete user
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteUser(int $id): JsonResponse
    {
        try {
            $this->userRepository->deleteUser($id);
            return $this->successResponse([], 'User deleted successfully');
        } catch (Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete user', 404);
        }
    }

    /**
     * get all users
     *
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        try {
            $users = $this->userRepository->getUsers();
            return $this->successResponse($users, 'Users retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error retrieving users: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve users', 404);
        }
    }
}
