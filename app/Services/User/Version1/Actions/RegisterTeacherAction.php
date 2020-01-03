<?php

namespace App\Services\User\Version1\Actions;

use App\Entities\User;
use App\Services\Action;
use App\Services\User\Version1\Tasks\RegisterUserTask;

class RegisterTeacherAction extends Action
{
    public function run($param)
    {
        return resolve(RegisterUserTask::class)->run($param, User::ROLE_TEACHER);
    }
}
