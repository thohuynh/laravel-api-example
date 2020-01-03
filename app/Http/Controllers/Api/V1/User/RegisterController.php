<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\BaseApiController;
use App\Services\User\Version1\Actions\RegisterTeacherAction;
use App\Http\Requests\User\V1\RegisterTeacherRequest;

class RegisterController extends BaseApiController
{
    public function registerTeacher(RegisterTeacherRequest $request)
    {
        $param = $request->only([
            'first_name',
            'last_name',
            'user_name',
            'email',
            'phone',
            'sex',
            'birthday',
            'password',
        ]);

        $result = resolve(RegisterTeacherAction::class)->run($param);

        return $this->responseOk($result);
    }
}
