<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\BaseApiController;
use App\Services\User\Version1\Actions\RegisterTeacherAction;
use App\Http\Requests\User\V1\RegisterTeacherRequest;
use Illuminate\Http\JsonResponse;

class RegisterController extends BaseApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/user/register-teacher",
     *     operationId="addPet",
     *     description="register-teacher",
     *     @OA\RequestBody(
     *         description="Pet to add to the store",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error"
     *     )
     * )
     *
     * @param $request RegisterTeacherRequest
     * @return JsonResponse
     */

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
