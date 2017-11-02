# laratest
Generic Test Suite For Laravel

## Introduction

This is meant to be a generic test suite for laravel.  It is meant to give a little reassurance that all api routes are protected by some authentication method.  This library should be used in addition to more specific testing, not in lieu of it

## Installation

```
composer install --dev blasher/laratest

cp -fr vendor/blasher/laratest/src/tests/ApiAuthTestInterface.php tests/.
cp -fr vendor/blasher/laratest/src/tests/ApiAuthenticatable.php tests/.
```

Then copy the appropriate file below:

```
cp -fr vendor/blasher/laratest/src/tests/ApiPassportAuthTest.php tests/.
cp -fr vendor/blasher/laratest/src/tests/ApiTokenAuthTest.php tests/.
```


## To do

  * fix authenticated tests (for both token and passport)
  * add code so that these files get added to laravel test suite automatically or thru artisan command
  * add passport usage test
  * modify passport authentication 


## License

blasher/laratest is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
