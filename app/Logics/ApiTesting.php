<?php

namespace App\Logics;

use App\Models\User;
use App\Repositories\TestingRulesRepository;
use JetBrains\PhpStorm\ArrayShape;

class ApiTesting
{
    //TODO - Add a method to check the rules

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

    /**
     * Start the test
     *
     * @param array $response
     * @param array $rules
     *
     * @return array
     */
    #[ArrayShape(['passed' => "int", 'failed' => "int", 'test_done' => "int", 'details' => 'array'])]
    public function testRequestResponse(array $response, array $rules): array
    {

        foreach ($response as $key => $values) {

            $this->iterateOverRules($values, $rules);

        }

        return [
            'passed' => $this->passed,
            'failed' => $this->failed,
            'test_done' => $this->testsDone,
            'details' => $this->details
        ];
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
