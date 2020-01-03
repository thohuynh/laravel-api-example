<?php

namespace App\Services\User\Version1\Tasks;

use App\Services\Task;
use App\Repositories\UserRepository;
use Hash;

class RegisterUserTask extends Task
{
    protected $userRepo;

    public function __construct()
    {
        $this->userRepo = app(UserRepository::class);
    }

    public function run($param, $role)
    {
        $param['password'] = Hash::make($param['password']);
        $param['role_id']  = $role;

        return $this->userRepo->query()->insert($param);
    }
}
