<?php

namespace App\Http\Controllers;

use App\Repositories\TestingRulesRepository;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;

class TestingController extends Controller
{

    private int    $passed = 0;
    private int    $failed = 0;
    private int    $testsDone = 0;
    private array  $details = [];
    private string $currentKey = '';
    private string $currentType = '';


    private TestingRulesRepository $testingRulesRepository;

    public function __construct() {
        $this->testingRulesRepository = new TestingRulesRepository();
    }

    #[ArrayShape(['articles' => "mixed", 'rules' => "array"])]
    private function newsApiTest(): array
    {
        $request = Http::get('https://newsapi.org/v2/everything?q=apple&from=2022-09-21&to=2022-09-21&sortBy=popularity&pageSize=2&apiKey=6cb38d870f3e465288db509687a37381');

        $response = json_decode($request->body(), true);

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

        return [[$response], $testingRules];
    }

    private function getLoggers() {
        $request = Http::post('https://pentest-tools.com/api?key=244cf074279f10e76d12fd2fb4f0fca027cd7515', [
            'op' => "get_loggers"
        ]);

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
                    'num_requests' => ['type' => 'integer'],
                    'requests_left' => ['type' => 'integer'],
                ]
            ]
        ];

        $response = json_decode($request->body(), true);

        return [[$response], $rules];
    }

    #[ArrayShape(['passed' => "int", 'failed' => "int", 'test_done' => "int"])]
    public function __invoke(): array
    {
        [$response, $testingRules] = $this->newsApiTest();

        return $this->testRequestResponse($response, $testingRules);
    }

    #[ArrayShape(['passed' => "int", 'failed' => "int", 'test_done' => "int"])]
    public function testRequestResponse(array $response, array $rules): array
    {

        foreach ($response as $key => $values) {

            $this->iterateOverRules($values, $rules);

            $this->testsDone++;
        }

        dd(['passed' => $this->passed, 'failed' => $this->failed, 'test_done' => $this->testsDone]);
    }

    private function iterateOverRules(array $values, array $testingRules)
    {
        foreach ($values as $keyValue => $value) {

            if (isset($testingRules[$keyValue])) {
                $currentRule = $testingRules[$keyValue];

                $this->currentType = $currentRule['type'];

                $this->checkTypes($currentRule, $value);
            }
        }
    }

    private function checkTypes(array $currentRule, mixed $value) {
        if ($this->currentType === 'array') {

            $this->passed += $this->testingRulesRepository->check($value, $this->currentType);

            if (isset($currentRule['collection'])) {
                foreach ($value as $collectionValue) {
                    $this->iterateOverRules($collectionValue, $currentRule['collection']);
                }
            } else {
                $this->iterateOverRules($value, $currentRule['object']);
            }

        } else if ($this->testingRulesRepository->check($value, $this->currentType)) {
            $this->passed++;
        } else {
            $this->failed++;
        }

    }
}
