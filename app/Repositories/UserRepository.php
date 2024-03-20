<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function showUsers()
    {
        return $this->model->get();
    }

    public function addUser($data)
    {
       return $this->model->create($data);
    }

    public function updateUser($user, $userData)
    {
        return $user->update($userData);
    }

    public function deleteUser($user)
    {
        return $user->delete();
    }

}