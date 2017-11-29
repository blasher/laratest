<?php

namespace Blasher\Laratest;

use App\Factory;
use App\User;
use DB;
use Exception;
use Illuminate\Http\Request as Request;
use Illuminate\Http\Response as Response;
use Route;
use Schema;

trait ApiAuthenticatable
{
    // TESTS

    /**
     * A basic test example.
     *
     * @test
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/')->assertSee('Laravel');
    }

    /**
     * A basic test example.
     *
     * @test
     * @depends testExample
     *
     * @todo pretty sure this test will always pass
     *
     * @return void
     */
    public function assertDBConnectionExists()
    {
        $pdo = '';

        try {
            $pdo = DB::connection()->getPdo();
        } catch (Exception $e) {
        }

        $this->assertTrue(is_object($pdo));
    }

    /**
     * A basic test example.
     *
     * @test
     * @depends testExample
     * @depends assertDBConnectionExists
     *
     * @return void
     */
    public function assertUserTableExists()
    {
        $this->assertTrue(Schema::hasTable('users'));
    }

    /**
     * Test to see if api has routes.
     *
     * @test
     * @depends testExample
     *
     * @return void
     */
    public function assertApiHasRoutes()
    {
        $hasApiRoutes = !empty($this->getApiRoutes());

        $this->assertTrue($hasApiRoutes);
    }

    /**
     * Test to ensure that factory exists for user class.
     *
     * @test
     * @depends testExample
     *
     * @return void
     */
    public function assertUserFactoryExists()
    {
        $user = '';

        try {
            $user = factory(User::class)->create();
        } catch (Exception $e) {
            $msg = 'User factory does not exist.';
            echo $msg."\n";
        }

        $this->assertTrue(is_object($user));
    }

    /**
     * Test to ensure that a user can be created.
     *
     * @test
     * @depends assertUserTableExists
     * @depends assertUserFactoryExists
     *
     * @return void
     */
    public function assertApiUserCanBeCreated()
    {
        $user = '';

        try {
            $user = $this->createApiUser();
        } catch (Exception $e) {
            $msg = 'User could not be created with factory.';
            echo $msg."\n";
        }

        $this->assertTrue(is_object($user));
    }

    /**
     * Test to determine whether all api routes are auth protected when unauthorized.
     *
     * @test
     * @depends assertApiUserCanBeCreated
     * @depends assertApiHasRoutes
     *
     * @return void
     */
    public function assertApiRoutesAreProtectedWhenUnauthorized()
    {
        $assertion = true;
        $routes = $this->getApiRoutes();

        foreach ($routes as $route) {
            if (!$this->assertApiRouteIsProtectedWhenUnauthorized($route)) {
                $assertion = false;
                break;
            }
        }

        $this->assertTrue($assertion);
    }

    /**
     * Test to determine whether all api routes are auth accessible when authorized.
     *
     * @test
     * @depends assertApiUserCanBeCreated
     * @depends assertApiHasRoutes
     *
     * @return void
     */
    public function assertApiRoutesAreAccessibleWhenAuthorized()
    {
        $assertion = true;
        $user = $this->createApiUser();
        $routes = $this->getApiRoutes();

        foreach ($routes as $route) {
            if (!$this->assertApiRouteIsAccessibleWhenAuthorized($user, $route)) {
                $assertion = false;
                break;
            }
        }

        $this->assertTrue($assertion);
    }

    // HELPERS

    /**
     * httpRequestMethods.
     *
     * @return array
     */
    public function httpRequestMethods()
    {
        return [
            'get',
            'post',
            'put',
            'delete',
            // 'patch',
            // 'head',
            // 'options',
            // 'connect',
        ];
    }

    /**
     * validResponseForunauthenticated.
     *
     * @return array
     */
    public function validResponseForUnauthenticated()
    {
        return [
           Response::HTTP_FOUND,              // 302
           Response::HTTP_UNAUTHORIZED,       // 401
           Response::HTTP_METHOD_NOT_ALLOWED, // 405
        ];
    }

    /**
     * Create api user.
     *
     * @return User
     */
    public function createApiUser()
    {
        return factory(User::class)->create();
    }

    /**
     * getApiRoutes.
     *
     * @return bool
     */
    public function getApiRoutes()
    {
        $allRoutes = Route::getRoutes()->getRoutes();
        $apiRoutes = [];

        if (empty($this->apiRoutes)) {
            $apiRoutes = $this->filterApiRoutes($allRoutes);
        }

        if (empty($apiRoutes)) {
            throw new Exception('No API routes found');
        }

        $this->cacheApiRoutes($apiRoutes);

        return $apiRoutes;
    }

    /**
     * filterApiRoutes.
     *
     * @param Illuminate\Routing\RouteCollection $routes
     *
     * @return Illuminate\Routing\RouteCollection
     */
    public function filterApiRoutes($routes)
    {
        $apiRoutes = [];

        foreach ($routes as $route) {
            if ($this->isApiRoute($route->uri())) {
                $apiRoutes[] = $route;
            }
        }

        return $apiRoutes;
    }

    /**
     * isApiRoute.
     *
     * @param string $uri
     *
     * @return bool
     */
    public function isApiRoute($uri)
    {
        return (bool) preg_match('/^api/', $uri);
    }

    /**
     * cacheApiRoutes.
     *
     * @param Illuminate\Routing\RouteCollection $routes
     *
     * @return bool
     */
    public function cacheApiRoutes($routes)
    {
        $this->apiRoutes = $routes;
    }

    /**
     * Determine whether a single api route is protected when unahthorized.
     *
     * @param Illuminate\Routing\Route $route
     *
     * @return bool
     *
     * @todo add tests for regular expressioned routes i.e. /api/user/{user}
     */
    public function assertApiRouteIsProtectedWhenUnauthorized($route)
    {
        return $this->getsErrorForUnauthenticatedRoute($route);
    }

    /**
     * Determine whether a single api route is accesible when authorized.
     *
     * @param User                     $user
     * @param Illuminate\Routing\Route $route
     *
     * @return bool
     *
     * @todo add tests for regular expressioned routes i.e. /api/user/{user}
     */
    public function assertApiRouteIsAccessibleWhenAuthorized($user, $route)
    {
        return $this->getsJsonForAuthenticatedRoute($user, $route);
    }

    /**
     * Check for unauthenticated error or redirect when not authenticated
     * for all http request methods given a route.
     *
     * @param Illuminate\Routing\Route $route
     */
    public function getsErrorForUnauthenticatedRoute($route)
    {
        $assertion = true;

        foreach ($this->httpRequestMethods() as $method) {
            if (!($this->getsErrorForUnauthenticatedRouteAndMethod($route, $method))) {
                $assertion = false;
            }
        }

        return $assertion;
    }

    /**
     * Check for unauthenticated error or redirect when not authenticated
     * given a route and http reuest method.
     *
     * @param Illuminate\Routing\Route $route
     * @param string                   $method
     */
    public function getsErrorForUnauthenticatedRouteAndMethod($route, $method)
    {
        $response = $this->$method($route->uri());
        $assertion = true;

        $validUnauthedResponses = $this->validResponseForUnauthenticated();
        $status = $response->getStatusCode();

        try {
            $this->assertContains($status, $validUnauthedResponses);
        } catch (Exception $e) {
            $assertion = false;

            $msg = 'Unprotected API route '.$route->uri.'.  Returned  '.$status.'.  ';
            $msg .= 'When unathenticated api should return '.implode(' or ', $validUnauthedResponses);
            echo $msg."\n";
        }

        return $assertion;
    }

    /**
     * Check for results when authenticated.
     *
     * @depends assertUserModelHasApiTokenProperty
     *
     * @param User                     $user
     * @param Illuminate\Routing\Route $route
     */
    public function getsJsonForAuthenticatedRoute($user, $route)
    {
        $assertion = true;

        foreach ($route->methods as $method) {
            if (!($this->getsJsonForAuthenticatedRouteAndMethod($user, $route, $method))) {
                $assertion = false;
            }
        }

        return $assertion;
    }

    /**
     * Check for results for a given route and method with authentication.
     *
     * @depends assertUserModelHasApiTokenProperty
     *
     * @param User                     $user
     * @param Illuminate\Routing\Route $route
     * @param string                   $method
     */
    public function getsJsonForAuthenticatedRouteAndMethod($user, $route, $method)
    {
        $assertion = true;
        $response = $this->makeApiCallWithAuthentication($user, $route, $method);

        try {
            $response->assertStatus(Response::HTTP_OK);
        } catch (Exception $e) {
            echo "\n".'Failed authentication for '.$method.' @ '.$route->uri()."\n";
            echo 'User api token: '.$user->api_token;
            $assertion = false;
        }

        return $assertion;
    }
}
