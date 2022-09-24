<?php

namespace Tests\Unit;

use App\Http\Controllers\TestingController;
use PHPUnit\Framework\TestCase;

class TestingApiTest extends TestCase
{
    /**
     * A common test for multiple responses
     *
     * @return array[]
     */
    public function apiParamsFromNewsApiProvider (): array
    {
        return [
          [
              [
                  'response' => [
                      0 => [
                          'source' => ['id' => 'engadget', 'name' => 'Engadget'],
                          'author' => "Mat Smith",
                          'title' => 'The Morning After: Peloton’s smart rowing machine is here',
                          'description' => 'After all the rumors and teasers, Peloton’s $3,195 rowing machine is finally official',
                          'url' => 'https://www.engadget.com/the-morning-after-pelotons-smart-rowing-machine-is-here-111534818.html',
                          'urlToImage' => 'https://s.yimg.com/os/creatr-uploaded-images/2022-09/54cbd9d0-38c2-11ed-bba5-f111fba92384',
                          'publishedAt' => '2022-09-21T11:15:34Z',
                          'content' => 'After all the rumors and teasers, Peloton’s $3,195 rowing machine is finally official doar ca mai mare',
                      ],
                      1 => [
                          'source' => ['id' => 'wired', 'name' => 'Wired'],
                          'author' => "Adrienne So",
                          'title' => 'The Morning After: Peloton’s smart rowing machine is here',
                          'description' => 'After all the rumors and teasers, Peloton’s $3,195 rowing machine is finally official',
                          'url' => 'https://www.engadget.com/the-morning-after-pelotons-smart-rowing-machine-is-here-111534818.html',
                          'urlToImage' => 'https://media.wired.com/photos/632a1fd0995b6da54b57efd1/191:100/w_1280,c_limit/Apple-Watch-Series-8-Review-Gear.jpg',
                          'publishedAt' => '2022-09-21T11:15:34Z',
                          'content' => 'After all the rumors and teasers, Peloton’s $3,195 rowing machine is finally official doar ca mai mare',
                      ]
                 ],
                  'rules' => [
                      'source' => [
                          'type' => 'array',
                          // 'keys' => 2, - to check the number of keys from object
                          'object' => [
                              'id' => [ 'type' => 'string' ],
                              'name' => [ 'type' => 'string'],
                          ]
                      ],
                      'author' => ['type' => 'string'],
                      'title' => ['type' => 'string'],
                      'description' => ['type' => 'int'],
                      'url' => ['type' => 'string|url'],
                  ]
              ],
              [
                  'passed' => 10,
                  'failed' => 4,
                  'test_done' => 2,
              ]
          ]
        ];
    }

    /** @dataProvider apiParamsFromNewsApiProvider */
    public function testApiParamsFromNewsApi ($input, $expectedValue) {

        $testingController = new TestingController();
        $result = $testingController->testRequestResponse($input['response'], $input['rules']);

        $this->assertEquals($expectedValue, $result);
    }
}
