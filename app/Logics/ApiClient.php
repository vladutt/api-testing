<?php

namespace App\Logics;

use Illuminate\Support\Facades\Http;

class ApiClient {

    private array $authMethods = [
        'basic', 'bearer', 'param'
    ];
    private array $requestMethods = [
        'post', 'get', 'put', 'patch', 'delete'
    ];

    public function __construct(
        public string $authMethod = '',
        public string $authToken = '',
        public string $endpoint = '',
        public string $requestMethod = '',
    ) {}

    /**
     * @throws \Exception
     */
    public function getResponse() {
        if ($this->checkAuthMethods()) {
            throw new \Exception('Invalid auth method: ' . $this->requestMethod . '.');
        }

        if ($this->checkRequestMethods()) {
            throw new \Exception('Invalid request method: ' . $this->authMethod . '.');
        }

    }

    private function checkAuthMethods(): bool
    {
        return !in_array($this->authMethod, $this->authMethods);
    }
    private function checkRequestMethods(): bool
    {
        return !in_array($this->requestMethod, $this->requestMethods);
    }

    private function sendRequest() {
        $requestMethod = $this->requestMethod;

        $request = new Http();

        switch ($this->authMethod) {
            case 'basic':
                $request->withHeaders([
                    'Authorization' => 'Bearer ' . $this->authToken
                ]);
                break;
            case 'bearer':
                $request->withHeaders([
                    'Authorization' => 'Basic ' . $this->authToken
                ]);
                break;
            default:
                break;
        }

        $request->$requestMethod($this->endpoint);

    }


}
