<?php

namespace App\Http\Controllers;

use App\Logics\ApiClient;
use App\Logics\ApiTesting;
use App\Models\Host;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use JetBrains\PhpStorm\ArrayShape;

class TestingController extends Controller
{

    public function hosts(): \Inertia\Response
    {
        $hosts = Host::all();

        return Inertia::render('Hosts', ['hosts' => $hosts]);
    }

    public function searchHosts(Request $request): \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $hosts = Host::where('host', 'LIKE', '%'.$request->searchTerm.'%')->get();

        return jsend_success($hosts);
    }

    #[ArrayShape(['articles' => "mixed", 'rules' => "array"])]
    private function newsApiTest(): array
    {
        $client = new ApiClient(
            authMethod: 'key_param',
            endpoint: 'https://newsapi.org/v2/everything?q=apple&from=2022-09-21&to=2022-09-21&sortBy=popularity&pageSize=2&apiKey=6cb38d870f3e465288db509687a37381',
            requestMethod: 'get'
        );

        $response = $client->getResponse();

        $testingRules = [
            'status' => ['type' => 'string'],
            'totalResults' => ['type' => 'integer'],
            'articles' => [
                'type' => 'array',
                'collection' => [
                    'source' => [
                        'type' => 'array',
                        // 'keys' => 2, - to check the number of keys from object
                        'object' => [
                            'id' => ['type' => 'string'],
                            'name' => ['type' => 'string'],
                        ]
                    ],
                    'author' => ['type' => 'string'],
                    'title' => ['type' => 'string'],
                    'description' => ['type' => 'int'],
                    'url' => ['type' => 'string|url'],
                ]
            ]
        ];

        return [$response, $testingRules];
    }

    /**
     * @throws \Exception
     */
    private function getLoggers(): array
    {
        $client = new ApiClient(
            authMethod: 'key_param',
            endpoint: 'https://pentest-tools.com/api?key=244cf074279f10e76d12fd2fb4f0fca027cd7515',
            requestMethod: 'post',
            parameters: ['op' => "get_loggers"]
        );

        $response = $client->getResponse();

        if ($response['client_error'] || $response['server_error']) {
            throw new \Exception('Request failed.');
        }

        $rules = [
            'op_status' => [
                'type' => 'string',
                'values' => 'success'
            ],
            'loggers' => [
               'type' => 'array',
                'collection' => [
                    'id' => ['type' => 'integer'],
                    'label' => ['type' => 'string'],
                    'handler_url' => ['type' => 'string'],
                    'active_days' => ['type' => 'integer'],
                    'num_requests' => ['type' => 'string'],
                    'requests_left' => ['type' => 'integer'],
                ]
            ]
        ];

        return [$response, $rules];
    }

    /**
     * @throws Exception
     */
    #[ArrayShape(['passed' => "int", 'failed' => "int", 'test_done' => "int"])]
    public function __invoke(): array
    {
        [$response, $testingRules] = $this->getLoggers();

        if ($response['client_error'] || $response['server_error']) {
            throw new Exception('The request was failed.');
        }

        $apiTesting = new ApiTesting();

        return $apiTesting->testRequestResponse([$response['body']], $testingRules);
    }


}
