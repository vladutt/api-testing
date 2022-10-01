<?php

namespace App\Logics;

use Exception;
use Illuminate\Support\Facades\Http;

class ApiClient {

    private array $authMethods = [
        'basic', 'bearer', 'key_param'
    ];
    private array $requestMethods = [
        'post', 'get', 'put', 'patch', 'delete'
    ];
    public mixed $response = null;

    public function __construct(
        public string $authMethod = '',
        public string $authToken = '',
        public string $endpoint = '',
        public string $requestMethod = '',
        public array $parameters = [],
    ) {}

    /**
     * @throws Exception
     */
    public function getResponse()
    {
        if ($this->checkAuthMethods()) {
            throw new Exception('Invalid auth method: ' . $this->requestMethod . '.');
        }

        if ($this->checkRequestMethods()) {
            throw new Exception('Invalid request method: ' . $this->authMethod . '.');
        }

        $this->sendRequest();

        return $this->response;
    }

    private function checkAuthMethods(): bool
    {
        return !in_array($this->authMethod, $this->authMethods);
    }

    private function checkRequestMethods(): bool
    {
        return !in_array($this->requestMethod, $this->requestMethods);
    }

    private function sendRequest(): void
    {
        $requestMethod = $this->requestMethod;

        $request = match ($this->authMethod) {
            'basic' => Http::withHeaders([
                'Authorization' => 'Basic ' . $this->authToken
            ]),
            'bearer' => Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->authToken
            ]),
            default => Http::withHeaders([]),
        };

        $request = $request->$requestMethod($this->endpoint, $this->parameters);

        $this->response = [
            'body' => $request->json(),
            'status' => $request->status(),
            'client_error' => $request->clientError(),
            'server_error' => $request->serverError(),
        ];
    }


}
