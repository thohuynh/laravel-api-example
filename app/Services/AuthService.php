<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use JWTAuth;
use Hash;

/**
 * Class AuthService.
 *
 * @package App\Services
 */
class AuthService
{
    /** @var $userRepository UserRepository */
    protected $userRepository;

    /**
     * AuthService constructor.
     */
    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
    }

    /**
     * Verify admin account with Email, Password.
     *
     * @param $username
     * @param $password
     * @return mixed
     */
    public function verify($username, $password)
    {
        $user = $this->userRepository->query()
            ->where('email', $username)
            ->orWhere('user_name', $username)
            ->orWhere('phone', $username)
            ->whereNull('deleted_at')
            ->first();

        if (empty($user)) {
            return false;
        }

        if (!Hash::check($password, $user->password)) {
            return false;
        }

        return $user;
    }

    /**
     * Generate token by User.
     *
     * @param $user
     * @return array
     */
    public function generateTokens($user)
    {
        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token, $user);
    }

    /**
     * Get the authenticated User.
     *
     * @return Authenticatable
     */
    public function me()
    {
        return auth()->user();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return void
     */
    public function invalidateToken()
    {
        return auth()->logout();
    }

    /**
     * Get the token array structure.
     *
     * @param $token
     * @param $user
     * @return array
     */
    protected function respondWithToken($token, $user)
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'user_name' => $user->user_name,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ]
        ];
    }
}
