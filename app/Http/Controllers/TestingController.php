<?php

namespace App\Http\Controllers;

use App\Logics\ApiClient;
use App\Logics\RestTesting;
use App\Models\Endpoint;
use App\Models\Host;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    public function addEndPoints(Request $request) {

        // TODO create an api call for this method - will be easy to add endpoints like this
        // TODO - endpoint-uri fara AUTH

        $validator  = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'path' => ['required', 'string'],
            'rules' => ['required','json'], // TODO - custom validation for rules
            'auth_method' => ['required', 'string'], // bearer / basic / param_key / user_pass
            'auth_params' => ['required', 'json'], // parameters for auth
            'parameters' => ['required', 'json']
        ]);

        if ($validator->fails()) {
            return jsend_fail($validator->errors()->all());
        }

        $host = Host::find(2);

        DB::beginTransaction();

        try {
            $endpoint = $host->endpoints()->create([
                'name' => $request->name,
                'path' => $request->path,
                'rules' => $request->rules,
                'host_id' => 2,
            ]);

            $endpoint->auth()->create([
                'auth_method' => $request->auth_method,
                'auth_params' => $request->auth_params,
            ]);

            $endpoint->parameters()->create([
                'parameters' => $request->parameters
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return jsend_error('Unexpected error... Please try again later.');
        }

        return jsend_success(['message' => 'The new endpoint was added.']);

    }

    #[ArrayShape(['articles' => "mixed", 'rules' => "array"])]
    private function newsApiTest(): array
    {
        $host = Host::with(['endpoints.auth', 'endpoints.parameters'])->find(3);

        $endpoint = $host->endpoints[0];

        $auth = $endpoint->auth;
        $parameters = $endpoint->parameters;


        $client = new ApiClient(
            authMethod: $auth['auth_method'],
            authParam: $auth->auth_params,
            endpoint: $host->host.$endpoint->path,
            requestMethod: $endpoint->method,
            parameters: $parameters->parameters
        );

        $response = $client->getResponse();

        return [$response, $endpoint->rules];
    }

    /**
     * @throws \Exception
     */
    private function getLoggers(): array
    {
        $host = Host::with(['endpoints.auth', 'endpoints.parameters'])->find(2);

        $endpoint = $host->endpoints[0];

        $auth = $endpoint->auth;
        $parameters = $endpoint->parameters;

        $client = new ApiClient(
            authMethod: $auth['auth_method'],
            authParam: $auth->auth_params,
            endpoint: 'https://'.$host->host.$endpoint->path,
            requestMethod: $endpoint->method,
            parameters: $parameters->parameters
        );

        $response = $client->getResponse();

        if ($response['client_error'] || $response['server_error']) {
            throw new \Exception('Request failed.');
        }

        $rules = $endpoint->rules;

        return [$response, $rules];
    }

    /**
     * @throws Exception
     */
    #[ArrayShape(['passed' => "int", 'failed' => "int", 'test_done' => "int"])]
    public function __invoke(): array
    {
        [$response, $testingRules] = $this->newsApiTest();

        if ($response['client_error'] || $response['server_error']) {
            throw new Exception('The request was failed.');
        }

        $apiTesting = new RestTesting();

        return $apiTesting->testRequestResponse($response['body'], $testingRules);
    }


}
