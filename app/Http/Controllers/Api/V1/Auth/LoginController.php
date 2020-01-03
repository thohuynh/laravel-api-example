<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class LoginController.
 *
 * @package App\Http\Controllers\Api\Auth
 */
class LoginController extends BaseApiController
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * LoginController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param Request $request .
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $params = $request->only('user_name', 'password');

        $user = $this->authService->verify($params['user_name'], $params['password']);

        if (!$user) {
            return $this->responseBadRequest('invalid_user');
        }

        $token = $this->authService->generateTokens($user);

        return $this->responseOk($token);
    }

    /**
     * Get user info.
     *
     * @return JsonResponse
     */
    public function me()
    {
        $me = $this->authService->me();

        return $this->responseOk($me);
    }

    /**
     * Logout admin account (remove current token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->authService->invalidateToken();

        return $this->responseOk();
    }
}
