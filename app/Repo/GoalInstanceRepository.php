<?php

namespace App\Repo;

use Illuminate\Support\Collection;
use App\Models\GoalInstance;
use App\Models\User;
use App\Models\Goal;

class GoalInstanceRepository
{
    protected $model;

    public function __construct(GoalInstance $goalInstanceModel)
    {
        $this->model = $goalInstanceModel;
    }

    /**
     * Create the goals' instances for the user
     *
     * @param User $user
     * @param string $date
     * @return void
     */
    public function createGoalInstances(User $user, string $date): void
    {
        // Step 1: Get the user's goals
        $goals = $user->goals()->get();

        // Step 2: Filter the goals that should be completed based on the date (days)
        $filteredGoals = $goals->filter(function ($goal) use ($date) {
            return $goal->shouldBeCompletedOn($date);
        });

        $filteredGoals = $filteredGoals->filter(function ($goal) use ($date) {
            $existingInstance = $this->model->where('goal_id', $goal->id)
                ->where('completion_date', $date)
                ->first();

            if ($existingInstance) {
                return false;
            }

            return true;
        });

        // Step 3: Create instances for the filtered goals
        $newInstances = $filteredGoals->map(function ($goal) use ($date) {
            return [
                'goal_id' => $goal->id,
                'completion_date' => $date,
                'completed_at' => null,
            ];
        })->toArray();

        $this->model->insert($newInstances);
    }

    /**
     * Get Goal Instances
     *
     * @param string|null $date
     * @return Collection
     */
    public function getGoalInstances(string|null $date = null): Collection
    {
        $goalInstances = !is_null($date) ? $this->model->where('completion_date', $date)->get() : $this->model->get();
        return $goalInstances;
    }

    /**
     * Get Goal Instance
     *
     * @param int $id
     * @return array
     */
    public function getGoalInstance(int $id): Collection
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update Goal Instances
     *
     * @param User $user
     * @param Goal $goal
     * @param string $date
     * @return void
     */
    public function updateGoalInstances(User $user, Goal $goal, string $date): void
    {
        // Delete all instances of the goal
        $this->model->where('goal_id', $goal->id)->delete();

        // Create instances for the goal for the date
        $this->createGoalInstances($user, $date);
    }

    /**
     * Delete Goal Instances
     *
     * @param User $user
     * @param int $goalId
     * @return void
     */
    public function deleteGoalInstances(User $user, int $goalId): void
    {
        // Delete all instances of the goal from the current date on
        $this->model->where('goal_id', $goalId)
                    ->where('completion_date', '>=', now()->format('Y-m-d'))
                    ->delete();
    }

    /**
     * Delete Goal Instance
     *
     * @param int $id
     * @return array
     */
    public function deleteGoalInstance(int $id): Collection
    {
        $goalInstance = $this->model->findOrFail($id);
        $goalInstance->delete();
        
        return $goalInstance;
    }
}
