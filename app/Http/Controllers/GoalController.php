<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Exception;
use App\Traits\ApiResponse;
use App\Services\GoalService;
use App\Helpers\ValidationHelper;

class GoalController extends Controller
{
    use ApiResponse;
    
    protected $goalService;

    public function __construct(GoalService $goalService)
    {
        $this->goalService = $goalService;
    }

    /**
     * Create a new goal
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createGoal(Request $request)
    {
        try {
            $request->validate(ValidationHelper::validateGoalCreation());
            return $this->goalService->createGoal($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Retrieve a specific goal
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getGoal(int $id)
    {
        try {
            return $this->goalService->getGoal($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update a goal
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateGoal(int $id, Request $request)
    {
        try {
            $request->validate(ValidationHelper::validateUpdateGoal());
            return $this->goalService->updateGoal($id, $request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete a goal
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteGoal(Request $request, int $id)
    {
        try {
            return $this->goalService->deleteGoal($request->all(), $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Retrieve all goals
     *
     * @return JsonResponse
     */
    public function getGoals()
    {
        try {
            return $this->goalService->getGoals();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }
}
