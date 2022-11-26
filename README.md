<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About ApiUnit

ApiUnit it's a service which has as a scope to facilitate the API testing.

For now, we have few rules like:
* string
* array
* integer
* float
* null
* url

Those rules can be used to validate the endpoint response from API.

## Example of usage
The response from API:
```
[
    'status' => 'success',
    'data' => [
        ['id' => 2, 'name' => 'An awesome Name', 'url' => 'https://google.ro'],
        ['id' => 3, 'name' => 'An awesome Name No. 2', 'url' => 'https://google.ro'],
    ]
] 
```
For this type of response the following rules should be set:
```
[
    'status' => ['type' => 'string'],
    'data'   => [
        'type' => 'array', 
        'collection' => [
            'id'   => ['type' => 'integer'],
            'name' => ['type' => 'string'],
            'url'   => ['type' => 'url'],
        ]
    ]
]
```
