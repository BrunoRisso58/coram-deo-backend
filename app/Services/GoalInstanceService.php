<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Repo\GoalInstanceRepository;
use App\Traits\ApiResponse;

class GoalInstanceService
{
    use ApiResponse;

    protected $goalInstanceRepository;

    public function __construct(GoalInstanceRepository $goalInstanceRepository)
    {
        $this->goalInstanceRepository = $goalInstanceRepository;
    }

    /**
     * Create the goals' instances for the user
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createGoalInstances(array $data): JsonResponse
    {
        try {
            $this->goalInstanceRepository->createGoalInstances($data['user'], $data['date']);
            return $this->successResponse([], 'Goal instances created successfully');
        } catch (Exception $e) {
            Log::error('Error creating goal instances: ' . $e->getMessage());
            return $this->errorResponse('Goal instances creation failed', 500);
        }
    }

    /**
     * Get Goal Instances
     *
     * @param array $data
     * @return JsonResponse
     */
    public function getGoalInstances(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $this->goalInstanceRepository->createGoalInstances($data['user'], $data['date']);
            $goalInstances = $this->goalInstanceRepository->getGoalInstances($data['date']);

            DB::commit();
            return $this->successResponse($goalInstances, 'Goal instances retrieved successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error retrieving goal instances: ' . $e->getMessage());
            return $this->errorResponse('Get goal instances failed', 500);
        }
    }

    /**
     * Get Goal Instance
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getGoalInstance(int $id): JsonResponse
    {
        try {
            $goalInstance = $this->goalInstanceRepository->getGoalInstance($id);
            return $this->successResponse($goalInstance, 'Goal instance retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error retrieving goal instance: ' . $e->getMessage());
            return $this->errorResponse('Get goal instance failed', 500);
        }
    }

    /**
     * Delete Goal Instance
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteGoalInstance(int $id): JsonResponse
    {
        try {
            $goalInstance = $this->goalInstanceRepository->deleteGoalInstance($id);
            return $this->successResponse($goalInstance, 'Goal instance deleted successfully');
        } catch (Exception $e) {
            Log::error('Error deleting goal instance: ' . $e->getMessage());
            return $this->errorResponse('Delete goal instance failed', 500);
        }
    }
}
