<?php

namespace Blasher\Laratest;

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
     * A basic test example.
     *
     * @test
     * @depends testExample
     * @todo pretty sure this test will always pass
     * @return void
     */
    public function assertDBConnectionExists();

    /**
     * A basic test example.
     *
     * @test
     * @depends testExample
     * @depends assertDBConnectionExists
     * @return void
     */
    public function assertUserTableExists();

    /**
     * Test to see if api has routes.
     *
     * @test
     * @depends testExample
     * @return void
     */
    public function assertApiHasRoutes();

    /**
     * Test to ensure that factory exists for user class.
     *
     * @test
     * @depends testExample
     * @return void
     */
    public function assertUserFactoryExists();

    /**
     * Test to ensure that a user can be created.
     *
     * @test
     * @depends assertUserTableExists
     * @depends assertUserFactoryExists
     * @return void
     */
    public function assertApiUserCanBeCreated();

    /**
     * Test to determine whether all api routes are auth protected.
     *
     * @test
     * @depends ensureApiUserCanBeCreated
     * @depends assertApiHasRoutes
     * @return void
     */
    public function apiRoutesRequireAuth();
    
    // HELPERS

    /**
     * httpRequestMethods.
     *
     * @return array
     */
    public function httpRequestMethods();
    
    /**
     * validResponseForunauthenticated
     *
     * @return array
     */
    public function validResponseForUnauthenticated();

    /**
     * getApiRoutes.
     *
     * @return bool
     */
    public function getApiRoutes();
    
    /**
     * filterApiRoutes.
     *
     * @param  Illuminate\Routing\RouteCollection $routes
     * @return Illuminate\Routing\RouteCollection
     */
    public function filterApiRoutes($routes);
    
    /**
     * isApiRoute.
     *
     * @param  string $uri
     * @return bool
     */
    public function isApiRoute($uri);

    /**
     * cacheApiRoutes.
     *
     * @param  Illuminate\Routing\RouteCollection $routes
     * @return bool
     */
    public function cacheApiRoutes($routes);
    
    /**
     * Determine whether a single api route is auth protected.
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
     * Check for results when authenticated.
     *
     * @param  Illuminate\Routing\Route $route
     */
    public function getsJsonForAuthenticatedRoute($route);
    
}
