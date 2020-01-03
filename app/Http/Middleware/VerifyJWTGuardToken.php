<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

/**
 * Class VerifyJWTGuardToken.
 *
 * @package App\Http\Middleware
 */
class VerifyJWTGuardToken extends BaseMiddleware
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $token = $request->bearerToken()) {
            return $this->responseUnauthorized('token_not_found');
        }

        try {
            /**
             * @var Model|mixed $user
             */
            $user = auth()->user();

            if (empty($user)) {
                return $this->responseUnauthorized('unauthenticated');
            }
        } catch (JWTException $e) {
            if ($e instanceof TokenInvalidException) {
                return $this->responseUnauthorized('token_invalid');
            }

            if ($e instanceof TokenExpiredException) {
                return $this->responseUnauthorized('token_expired');
            }

            return $this->responseUnauthorized('token_internal_error');
        }

        return $next($request);
    }
}
