# laratest
Generic Test Suite For Laravel

## Introduction

This is meant to be a generic test suite for laravel.  It is meant to give a little reassurance that all api routes are protected by some authentication method.  This library should be used in addition to more specific testing, not in lieu of it

## Installation

```
composer install --dev blasher/laratest
```

Then publush the appropriate tests via the commands below:

```
php artisan vendor:publish --tag=laratest_token
```
or
```
php artisan vendor:publish --tag=laratest_passport
```


## To do

  * fix token auth tests
  * write code for passport authentication


## License

blasher/laratest is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
