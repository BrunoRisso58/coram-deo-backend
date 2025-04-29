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
    public function getUser(int $id): JsonResponse
    {
        return $this->userService->getUser($id);
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

    /**
     * Authenticates a user with the provided credentials.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate(ValidationHelper::getLoginData());
            return $this->userService->login($request->all());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_UNPROCESSABLE_ENTITY);
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
            return $this->userService->logout();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Check if the email can be used
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkEmail(Request $request): JsonResponse
    {
        try {
            $request->validate(ValidationHelper::getCheckEmailData());
            return $this->userService->checkEmail($request->all());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
