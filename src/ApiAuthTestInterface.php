<?php

namespace Blasher\Laratest;

use App\Factory;
use App\User;
use Exception;
use Route;
use Schema;

interface ApiAuthTestInterface
{
  
    // TESTS

    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function testExample();

    /**
     * Test to see if api has routes.
     *
     * @test
     * @return void
     */
    public function ensureApiHasRoutes();

    /**
     * Test ensure that user table is set up for token aunthentication.
     *
     * @test
     * @return void
     */
    public function ensureUserTableHasApiTokenParam();

    /**
     * Test to ensure that factory exists for user class.
     *
     * @test
     * @return void
     */
    public function ensureUserFactoryExists();

    /**
     * Test to ensure that user exists .
     *
     * @test
     * @return void
     */
    public function ensureUserApiUser();

    /**
     * test to determine whether all api routes are auth protected
     *
     * @test
     * @return void
     */
    public function apiRoutesRequireAuth();
    
    // HELPERS

    /**
     * getApiRoutes
     *
     * @return bool
     */
    public function getApiRoutes();
    
    /**
     * filterApiRoutes
     *
     * @param  Illuminate\Routing\RouteCollection $routes
     * @return Illuminate\Routing\RouteCollection
     */
    public function filterApiRoutes($routes);
    
    /**
     * isApiRoute
     *
     * @param  string $uri
     * @return bool
     */
    public function isApiRoute($uri);

    /**
     * cacheApiRoutes
     *
     * @param  Illuminate\Routing\RouteCollection $routes
     * @return bool
     */
    public function cacheApiRoutes($routes);
    
    /**
     * determine whether a single api route is auth protected
     *
     * @param  Illuminate\Routing\Route $route
     * @return bool
     *
     * @todo add tests for regular expressioned routes i.e. /api/user/{user}
     */
    public function apiRouteRequiresAuth($route);
    
    /**
     * Check for unauthenticated error or redirect when not authenticated
     * for all http request methods given a route.
     *
     * @param  Illuminate\Routing\Route $route
     */
    public function getsErrorForUnauthenticatedRoute($route);

    /**
     * Check for unauthenticated error or redirect when not authenticated
     * given a route and http reuest method.
     *
     * @param  Illuminate\Routing\Route $route
     * @param  string $method
     */
    public function getsErrorForUnauthenticatedRouteAndMethod($route,$method);
    
    /**
     * Check for results when authenticated
     *
     * @param  Illuminate\Routing\Route $route
     */
    public function getsJsonForAuthenticatedRoute($route);
    
    /**
     * create authenticated api user
     *
     * @return User
     */
    public function createApiUser();
    
}
