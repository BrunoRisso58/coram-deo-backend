<?php

namespace App\Repo;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository
{
    protected $model;

    public function __construct(User $userModel)
    {
        $this->model = $userModel;
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return array
     */
    public function createUser(array $data): array
    {
        $user = $this->model->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        return $user->only(['name', 'phone', 'email']);
    }

    /**
     * get user by id
     *
     * @param int $id
     * @return array
     */
    public function getUser(int $id): array
    {
        $user = $this->model->where('id', $id)->firstOrFail();
        return $user->only(['name', 'phone', 'email', 'role']);
    }

    /**
     * update user
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateUser(int $id, array $data): array
    {
        $user = $this->model->find($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->update($data);

        return $user->refresh()->only(['name', 'phone', 'email', 'role']);
    }

    /**
     * delete user
     *
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        $user = $this->model->find($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->delete();
    }

    /**
     * get all users
     *
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->model->get();
    }

    /**
     * Authenticates a user with the provided credentials.
     *
     * @param array $data
     * @return string
     */
    public function login(array $data): string
    {
        $token = JWTAuth::attempt($data);
        return $token;
    }

    public function getByColumn($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    /**
     * Gets the user associated with the provided token.
     *
     * @return User|bool
     */
    public function getAuthenticatedUser(): User|bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user;
    }
}
