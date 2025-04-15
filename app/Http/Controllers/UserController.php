<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Exception;
use App\Traits\ApiResponse;
use App\Services\UserService;
use App\Helpers\ValidationHelper;

class UserController extends Controller
{
    use ApiResponse;
    
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create a new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signUp(Request $request)
    {
        try {
            $request->validate(ValidationHelper::validateUserCreation());
            return $this->userService->signUp($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
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
        return $this->userService->getUser($id, $request);
    }

    /**
     * update user
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUser(int $id, Request $request): JsonResponse
    {
        try {
            $request->validate(ValidationHelper::validateUserUpdate());
            return $this->userService->updateUser($id, $request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * delete user
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteUser(int $id, Request $request): JsonResponse
    {
        try {
            return $this->userService->deleteUser($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * get all users
     *
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        return $this->userService->getUsers();
    }
}
