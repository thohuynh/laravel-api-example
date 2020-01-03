<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Whoops\Exception\ErrorException;
use Log;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($request->wantsJson()) {
            if ($exception instanceof ValidationException) {
                return $this->responseBadRequest($exception->validator->errors()->first());
            }

            if ($exception instanceof AuthenticationException) {
                return $this->responseUnauthorized('token_expired');
            }

            if ($exception instanceof AuthorizationException) {
                return $this->responseUnauthorized('unauthenticated');
            }

            if ($exception instanceof ResourceNotFoundException) {
                return $this->responseErrorInternal($exception->getMessage(), $exception->getCode());
            }

            if ($exception instanceof ErrorException) {
                Log::error('[HTTP_INTERNAL_SERVER_ERROR]' . $exception);
                return $this->responseErrorInternal('http_internal_error', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            Log::error('[HTTP_INTERNAL_SERVER_ERROR]' . $exception);
            return $this->responseErrorInternal('http_internal_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $exception);
    }
}
