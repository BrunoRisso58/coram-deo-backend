<?php

namespace App\Repo;

use Illuminate\Support\Collection;
use App\Models\Goal;

class GoalRepository
{
    protected $model;

    public function __construct(Goal $goalModel)
    {
        $this->model = $goalModel;
    }

    /**
     * Create a new goal
     *
     * @param array $data
     * @return Goal
     */
    public function createGoal(array $data): Goal
    {
        $goal = $this->model->create([
            'user_id' => $data['user']->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'days' => $data['days'],
            'reminder_days' => $data['reminder_days'] ?? null,
            'reminder_time' => $data['reminder_time'] ?? null,
        ]);

        return $goal;
    }

    /**
     * Retrieve a specific goal
     *
     * @param int $id
     * @return Goal
     */
    public function getGoal(int $id): Goal
    {
        $goal = $this->model->findOrFail($id);
        return $goal;
    }

    /**
     * Update a goal
     *
     * @param int $id
     * @param array $data
     * @return Goal
     */
    public function updateGoal(int $id, array $data): Goal
    {
        $goal = $this->model->find($id);

        if (!$goal) {
            throw new \Exception('Goal not found');
        }

        $goal->update($data);

        return $goal->refresh();
    }

    /**
     * Delete a goal
     * 
     * @param int $id
     * @return Goal
     */
    public function deleteGoal(int $id): Goal
    {
        $goal = $this->model->find($id);

        if (!$goal) {
            throw new \Exception('Goal not found');
        }

        $goal->delete();

        return $goal;
    }

    /**
     * Retrieve all goals
     *
     * @return Collection
     */
    public function getGoals(): Collection
    {
        $goals = $this->model->all();
        return $goals;
    }
}
