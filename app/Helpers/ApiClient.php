<?php

namespace App\Helpers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Log;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ApiClient
{
    /** @var Client */
    protected $client;

    /** @var ResponseInterface */
    protected $response;

    /** @var string $uri */
    protected $uri;

    /** @var string */
    protected $bodyType = RequestOptions::JSON;

    /** @var array $body */
    protected $body;

    /**
     * Headers of request.
     * @var array $headers
     */
    protected $headers;

    /**
     * Config of request.
     * @var array $options
     */
    protected $options;

    /**
     * ApiClient constructor.
     *
     * @param string $baseUri
     * @param array $headers
     */
    public function __construct(string $baseUri = '', array $headers = [])
    {
        $defaultOptions = [
            'base_uri' => empty($baseUri) ? config('app.api_url') : $baseUri,
            'headers'  => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json'
            ],
        ];
        $this->options  = $defaultOptions;
        $this->mergeOptions([
            'headers' => $headers
        ]);

        $this->client = new Client($this->options);
    }

    /**
     * Get response string.
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response->getBody()->getContents();
    }

    /**
     * Set headers of request.
     *
     * @param array $headers
     * @return $this
     */
    public function withHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set body of request.
     *
     * @param array|null $body
     * @return ApiClient
     */
    public function withBody(array $body = null)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set body type of request is query string.
     *
     * @return $this
     */
    public function asQuery()
    {
        $this->bodyType = RequestOptions::QUERY;

        return $this;
    }

    /**
     * Set body type of request is form params.
     *
     * @return $this
     */
    public function asFormParams()
    {
        $this->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded']);
        $this->bodyType = RequestOptions::FORM_PARAMS;

        return $this;
    }

    /**
     * Set body type of request is json.
     *
     * @return $this
     */
    public function asJson()
    {
        $this->withHeaders(['Content-Type' => 'application/json']);
        $this->bodyType = RequestOptions::JSON;

        return $this;
    }

    /**
     * Set body type of request is multipart.
     *
     * @return $this
     */
    public function asMultipart()
    {
        $this->withHeaders(['Content-Type' => 'multipart/form-data']);
        $this->bodyType = RequestOptions::MULTIPART;

        return $this;
    }

    /**
     * Define path to send request.
     *
     * @param string $endpoint
     * @return $this
     */
    public function to(string $endpoint)
    {
        $this->uri = $endpoint;

        return $this;
    }

    /**
     * @return mixed|string
     * @throws GuzzleException
     */
    public function get()
    {
        $this->asQuery();

        return $this->makeRequest('GET');
    }

    /**
     * Send post request.
     *
     * @return mixed|string
     * @throws GuzzleException
     */
    public function post()
    {
        return $this->makeRequest('POST');
    }

    /**
     * Send put request.
     *
     * @return mixed|string
     * @throws GuzzleException
     */
    public function put()
    {
        return $this->makeRequest('PUT');
    }

    /**
     * Send delete request.
     *
     * @return mixed|string
     * @throws GuzzleException
     */
    public function delete()
    {
        return $this->makeRequest('DELETE');
    }

    /**
     * Merge options.
     *
     * @param array $options
     */
    private function mergeOptions(array $options)
    {
        $this->options = array_merge_recursive_distinct($this->options, $options);
    }

    /**
     * Send Request.
     *
     * @param $method
     * @return mixed|string
     * @throws GuzzleException
     */
    private function makeRequest($method)
    {
        $config = [
            $this->bodyType         => $this->body,
            RequestOptions::HEADERS => $this->headers,
        ];
        $this->mergeOptions($config);
        $this->response = $this->client->request($method, $this->uri, $this->options);

        return $this->handleResponse();
    }

    /**
     * Handle response
     *
     * @return mixed|string
     */
    private function handleResponse()
    {
        $statusCode = $this->response->getStatusCode();

        if ($statusCode === Response::HTTP_OK) {
            return $this->parseResponse();
        }

        Log::error("[API_CLIENT][$statusCode]" . $this->getResponse());

        return null;
    }

    /**
     * Return object if response is json, otherwise return string itself.
     *
     * @param bool $assoc
     * @return object|string
     */
    private function parseResponse($assoc = false)
    {
        $response = $this->getResponse();

        try {
            return json_decode($response, $assoc);
        } catch (Exception $e) {
            return $response;
        }
    }
}
