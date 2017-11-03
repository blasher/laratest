<?php

namespace Blasher\Laratest;

use Illuminate\Support\ServiceProvider;

class LaratestServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/tests/API/ApiAuthenticatable.php'   => $this->test_path('/API/ApiAuthenticatable.php'),
            __DIR__.'/tests/API/ApiAuthTestInterface.php' => $this->test_path('/API/ApiAuthTestInterface.php'),
            __DIR__.'/tests/API/ApiTokenAuthTest.php'     => $this->test_path('/API/ApiTokenAuthTest.php.php')
        ], 'laratest_token');

        $this->publishes([
            __DIR__.'/tests/API/ApiAuthenticatable.php'   => $this->test_path('/API/ApiAuthenticatable.php'),
            __DIR__.'/tests/API/ApiAuthTestInterface.php' => $this->test_path('/API/ApiAuthTestInterface.php'),
            __DIR__.'/tests/API/ApiPassportAuthTest.php'  => $this->test_path('/API/ApiPassportAuthTest.php')
        ], 'laratest_passport');

       //        parent::boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/tests' . ($path ? '/' . $path : $path);
    }
    
}
