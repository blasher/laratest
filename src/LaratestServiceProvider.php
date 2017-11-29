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
            __DIR__.'/Tests/API/ApiTokenAuthTest.php'    => $this->tests_path('/API/ApiTokenAuthTest.php'),
        ], 'laratest_token');

        $this->publishes([
            __DIR__.'/Tests/API/ApiPassportAuthTest.php' => $this->tests_path('/API/ApiPassportAuthTest.php'),
        ], 'laratest_passport');
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
     * Get the tests path.
     *
     * @param string $path
     *
     * @return string
     */
    public function tests_path($path = '')
    {
        return app()->basePath().'/tests'.($path ? '/'.$path : $path);
    }
}
