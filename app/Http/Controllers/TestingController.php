<?php

namespace App\Http\Controllers;

use App\Logics\ApiClient;
use App\Models\User;
use App\Repositories\TestingRulesRepository;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;

class TestingController extends Controller
{

    // Numbers of the results and details about those tests
    private int    $passed = 0;
    private int    $failed = 0;
    private int    $testsDone = 0;
    private array  $details = [];

    /**
     * Name of the current field that is checked
     */
    private string $currentKey = '';

    /**
     * Current type to be checked. ex: string, integer, array...
     */
    private string $currentType = '';


    private TestingRulesRepository $testingRulesRepository;

    public function __construct() {
        $this->testingRulesRepository = new TestingRulesRepository();
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

    #[ArrayShape(['passed' => "int", 'failed' => "int", 'test_done' => "int"])]
    public function __invoke(): array
    {
        [$response, $testingRules] = $this->getLoggers();

        if ($response['client_error'] || $response['server_error']) {
            throw new \Exception('The request was failed.');
        }

        return $this->testRequestResponse([$response['body']], $testingRules);
    }

    /**
     * Start the test
     *
     * @param array $response
     * @param array $rules
     *
     * @return array
     */
    #[ArrayShape(['passed' => "int", 'failed' => "int", 'test_done' => "int"])]
    public function testRequestResponse(array $response, array $rules): array
    {

        foreach ($response as $key => $values) {

            $this->iterateOverRules($values, $rules);

        }

        dd(['passed' => $this->passed, 'failed' => $this->failed, 'test_done' => $this->testsDone, 'details' => $this->details]);
    }

    /**
     * Iterate over specified rules
     *
     * @param array $values
     * @param array $testingRules
     *
     * @return void
     */
    private function iterateOverRules(array $values, array $testingRules): void
    {
        foreach ($values as $keyValue => $value) {

            if (isset($testingRules[$keyValue])) {
                $currentRule = $testingRules[$keyValue];
                $this->currentKey = $keyValue;

                $this->currentType = $currentRule['type'];

                $this->checkTypes($currentRule, $value);
            }
        }
    }

    /**
     * Check rules/types
     *
     * @param array $currentRule
     * @param mixed $value
     *
     * @return void
     */
    private function checkTypes(array $currentRule, mixed $value): void
    {

        $test = User::count();
        if ($this->currentType === 'array') {

            $checkResult = $this->testingRulesRepository->check($value, $this->currentType);
            $this->incrementPassedOrFailedAndLog($checkResult, $value);

            if (isset($currentRule['collection'])) {
                foreach ($value as $collectionValue) {
                    $this->iterateOverRules($collectionValue, $currentRule['collection']);
                }
            } else {
                $this->iterateOverRules($value, $currentRule['object']);
            }

        } else {
            $checkResult = $this->testingRulesRepository->check($value, $this->currentType);
            $this->incrementPassedOrFailedAndLog($checkResult, $value);
        }

        $this->testsDone++;
    }

    /**
     * Increment passed or failed tests and log the details about every check
     *
     * @param bool $result
     * @param mixed $value
     *
     * @return void
     */
    private function incrementPassedOrFailedAndLog(bool $result, mixed $value): void
    {
        $passedOrFailed = 'passed';
        $failedDetails = '';

        $valueToBeDisplayed = is_array($value) ? json_encode($value) : $value;
        $valueType = gettype($value);

        if (!$result) {
            $passedOrFailed = 'failed';
            $failedDetails = trans('validation.'.$this->currentType, ['attribute' => $this->currentKey]) . " Actual value: $valueToBeDisplayed";
        }

        $this->details[$passedOrFailed][] = $this->currentKey . ' has ' . $passedOrFailed . '. ' . $failedDetails;


        $this->$passedOrFailed++;
    }
}
