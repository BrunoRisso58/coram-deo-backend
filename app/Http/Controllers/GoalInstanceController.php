<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\GoalInstanceService;
use App\Helpers\ValidationHelper;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Exception;
use Illuminate\Http\JsonResponse;

class GoalInstanceController extends Controller
{
    use ApiResponse;
    
    protected $goalInstanceService;

    public function __construct(GoalInstanceService $goalInstanceService)
    {
        $this->goalInstanceService = $goalInstanceService;
    }

    /**
     * Create the goals' instances for the user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createGoalInstances(Request $request)
    {
        try {
            $request->validate(ValidationHelper::validateGoalInstancesCreation());
            return $this->goalInstanceService->createGoalInstances($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get Goal Instances
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getGoalInstances(Request $request)
    {
        try {
            $request->validate(ValidationHelper::validateGetGoalInstances());
            return $this->goalInstanceService->getGoalInstances($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get Goal Instance
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getGoalInstance(int $id)
    {
        try {
            return $this->goalInstanceService->getGoalInstance($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete goal instance
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteGoalInstance(int $id)
    {
        try {
            return $this->goalInstanceService->deleteGoalInstance($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Mark goal instance as complete
     *
     * @param int $id
     * @return JsonResponse
     */
    public function markGoalInstanceAsComplete(int $id)
    {
        try {
            return $this->goalInstanceService->markGoalInstanceAsComplete($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get dashboard for the last 7 days completion
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function get7DaysCompletionDashboard(Request $request): JsonResponse
    {
        try {
            return $this->goalInstanceService->get7DaysCompletionDashboard($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseStatus::HTTP_BAD_REQUEST);
        }
    }
}
