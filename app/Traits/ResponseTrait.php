<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Trait ResponseTrait.
 *
 * @package App\Contracts
 */
trait ResponseTrait
{
    /**
     * Response success structure.
     *
     * @param $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success($data, $message = 'SUCCESS', $code = Response::HTTP_OK)
    {
        return response()->json(
            [
                'status'  => true,
                'code'    => $code,
                'data'    => $data,
                'message' => $message,
            ],
            $code
        );
    }

    /**
     * Response error structure.
     *
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    protected function error($message, $code = Response::HTTP_BAD_REQUEST)
    {
        $decode = is_string($message) ? json_decode($message) : '';
        if ($decode) {
            $message = $decode;
        }

        return response()->json(
            [
                'status'  => false,
                'code'    => $code,
                'data'    => null,
                'message' => $message,
            ],
            $code
        );
    }

    /**
     * Response OK (200).
     *
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    public function responseOk($data = [], $message = 'RESPONSE_OK')
    {
        return $this->success($data, $message, Response::HTTP_OK);
    }

    /**
     * Response Bad Request (400).
     *
     * @param string $message
     * @return JsonResponse
     */
    public function responseBadRequest($message = 'RESPONSE_BAD_REQUEST')
    {
        return $this->error($message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Response Unauthorized (403).
     *
     * @param string $message
     * @return JsonResponse
     */
    public function responseUnauthorized($message = 'RESPONSE_UNAUTHORIZED')
    {
        return $this->error($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Response Not Found (404).
     *
     * @param string $message
     * @return JsonResponse
     */
    public function responseNotFound($message = 'RESPONSE_NOT_FOUND')
    {
        return $this->error($message, Response::HTTP_NOT_FOUND);
    }


    /**
     * Response Error Server (500).
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function responseErrorInternal($message = 'RESPONSE_INTERNAL_SERVER_ERROR', $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return $this->error($message, $statusCode);
    }
}
