<?php

namespace App\Domains\Shop\User\Repositories;

use App\Domains\Shop\User\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->findById($id);
        
        if (!$user) {
            return null;
        }
        
        $user->update($data);
        return $user;
    }
}