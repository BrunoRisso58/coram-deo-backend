<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Exception;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
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
     * @return JsonResponse
     */
    public function getUser(int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->getUser($id);

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

    /**
     * Authenticates a user with the provided credentials.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function login(array $data): JsonResponse
    {
        try {
            $token = $this->userRepository->login($data);

            if (!$token) {
                return $this->errorResponse('Email ou senha inválidos', ResponseStatus::HTTP_UNAUTHORIZED);
            }

            return $this->successResponse([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'user' => JWTAuth::user()
            ], 'Login feito com sucesso!');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Logs out the authenticated user.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->successResponse([], 'Logout feito com sucesso!');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Check if the email can be used
     *
     * @param array $data
     * @return JsonResponse
     */
    public function checkEmail(array $data): JsonResponse
    {
        try {
            $user = $this->userRepository->getByColumn('email', $data['email']);

            if (!$user->isEmpty()) {
                return $this->errorResponse('O email já está em uso', ResponseStatus::HTTP_BAD_REQUEST);
            }

            return $this->successResponse([], 'Email disponível');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
