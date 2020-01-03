<?php

namespace App\Http\Middleware;

use Closure;

class BasicAuthLogsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user     = config('basic-auth.user_log');
        $password = config('basic-auth.password_log');
        $envs     = config('basic-auth.envs_log');
        $errorMsg = config('basic-auth.error_message_log');

        // Check if middleware is in use in current environment
        if (in_array(app()->environment(), $envs)) {
            if ($request->getUser() != $user || $request->getPassword() != $password) {
                $headers = ['WWW-Authenticate' => 'Basic'];

                return response($errorMsg, 401, $headers);
            }
        }

        return $next($request);
    }
}
