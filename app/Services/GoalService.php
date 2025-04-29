<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Exception;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Repo\GoalRepository;
use App\Repo\GoalInstanceRepository;
use App\Traits\ApiResponse;

use function PHPUnit\Framework\arrayHasKey;

class GoalService
{
    use ApiResponse;

    protected $goalRepository;
    protected $goalInstanceRepository;

    public function __construct(GoalRepository $goalRepository, GoalInstanceRepository $goalInstanceRepository)
    {
        $this->goalRepository = $goalRepository;
        $this->goalInstanceRepository = $goalInstanceRepository;
    }

    /**
     * Create a new goal
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createGoal(array $data): JsonResponse
    {
        try {
            $goal = $this->goalRepository->createGoal($data);
            return $this->successResponse($goal, 'Goal created successfully');
        } catch (Exception $e) {
            Log::error('Error creating goal: ' . $e->getMessage());
            return $this->errorResponse('Goal creation failed', 500);
        }
    }

    /**
     * Retrieve a specific goal
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getGoal(int $id): JsonResponse
    {
        try {
            $goal = $this->goalRepository->getGoal($id);
            return $this->successResponse($goal, 'Goal retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error retrieving goal: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving goal', 500);
        }
    }

    /**
     * Update a goal
     *
     * @param int $id
     * @param array $data
     * @return JsonResponse
     */
    public function updateGoal(int $id, array $data): JsonResponse
    {
        try {
            DB::beginTransaction();
            $goal = $this->goalRepository->updateGoal($id, $data);

            if (array_key_exists('days', $data))
                $this->goalInstanceRepository->updateGoalInstances($data['user'], $goal, $data['date']);

            DB::commit();
            return $this->successResponse($goal, 'Goal updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating goal: ' . $e->getMessage());
            return $this->errorResponse('Error updating goal', 500);
        }
    }

    /**
     * Delete a goal
     *
     * @param array $data
     * @param int $id
     * @return JsonResponse
     */
    public function deleteGoal(array $data, int $id)
    {
        try {
            $goal = $this->goalRepository->deleteGoal($id);

            if (!$goal) {
                return $this->errorResponse('Goal not found', 404);
            }

            // Delete goal instances associated with the goal
            $this->goalInstanceRepository->deleteGoalInstances($data['user'], $id);

            return $this->successResponse($goal, 'Goal deleted successfully');
        } catch (Exception $e) {
            Log::error('Error deleting goal: ' . $e->getMessage());
            return $this->errorResponse('Error deleting goal', 500);
        }
    }

    /**
     * Retrieve all goals
     *
     * @return JsonResponse
     */
    public function getGoals(): JsonResponse
    {
        try {
            $goals = $this->goalRepository->getGoals();
            return $this->successResponse($goals, 'Goals retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error retrieving goals: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving goals', 500);
        }
    }
}
