# laratest
Generic Test Suite For Laravel

## Introduction

This is meant to be a generic test suite for laravel.  It is meant to give a little reassurance that all api routes are protected by some authentication method.  This library should be used in addition to more specific testing, not in lieu of it

## Installation

1) Require Package

```
composer requre blasher/laratest
```

2) Authorization Scaffolding

If you haven't already built your authorization scaffolding, make sure do to

```
php artisan make:auth
```

or tests will fail due to 500 errors when redirecting to "/login" route


3) Publish tests

Then publush the appropriate tests via the commands below:

```
php artisan vendor:publish --tag=laratest_token
```
or
```
php artisan vendor:publish --tag=laratest_passport
```

## To do

  * modify token auth test to compare data returned from api call to internally generated route output
  * write code for passport authentication testing


## License

blasher/laratest is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
