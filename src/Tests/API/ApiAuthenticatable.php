<?php

namespace Blasher\Laratest\Tests\API;

use App\Factory;
use App\User;
use Exception;
use Illuminate\Http\Response as Response;
use Tests\TestCase;
use Route;
use Schema;

trait ApiAuthenticatable
{
    // TESTS

    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function testExample()
    {
        $this->get('/')->assertSee('Laravel');
    }


    /**
     * Test to see if api has routes.
     *
     * @test
     * @return void
     */
    public function ensureApiHasRoutes()
    {
        $hasApiRoutes = !empty($this->getApiRoutes());

        $this->assertTrue($hasApiRoutes);
    }


    /**
     * Test ensure that user table is set up for token aunthentication.
     *
     * @test
     * @return void
     */
    public function ensureUserTableHasApiTokenParam()
    {
        $this->assertTrue( Schema::hasColumn('users', 'api_token') );
    }


    /**
     * Test to ensure that factory exists for user class.
     *
     * @test
     * @return void
     */
    public function ensureUserFactoryExists()
    {
        try {
            $user = factory(User::class)->create();
        } catch (Exception $e)
        {
            $msg  = 'User factory does not exist';
            echo ( $msg . "\n" );
        }

        $this->assertTrue( is_object($user) );
    }


    /**
     * Test to ensure that user exists .
     *
     * @test
     * @return void
     */
    public function ensureUserApiUser()
    {
        try {
            $user = factory(User::class)->create();
        } catch (Exception $e)
        {
            $msg  = 'User could not be created with factory.';
            echo ( $msg . "\n" );
        }

        $this->assertTrue( is_object($user) );
    }


    /**
     * test to determine whether all api routes are auth protected
     *
     * @test
     * @return void
     */
    public function apiRoutesRequireAuth()
    {
        $allRoutesRequireAuth = true;
        $routes = $this->getApiRoutes();

        foreach ($routes as $route)  {
            $requiresAuth = $this->apiRouteRequiresAuth( $route );

            if(!$requiresAuth)
            {  $allRoutesRequireAuth = false;
               break;
            }
        }                

        $this->assertTrue($allRoutesRequireAuth);
    }

    
    // HELPERS

    /**
     * getApiRoutes
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

        if (empty($apiRoutes)){
            throw new Exception ('No API routes found');
        }

        $this->cacheApiRoutes($apiRoutes);

        return $apiRoutes;
    }

    
    /**
     * filterApiRoutes
     *
     * @param  Illuminate\Routing\RouteCollection $routes
     * @return Illuminate\Routing\RouteCollection
     */
    public function filterApiRoutes($routes)
    {
        $apiRoutes = [];

        foreach($routes as $route){
            if( $this->isApiRoute($route->uri())) {
                $apiRoutes[] = $route;
            }
        }

        return $apiRoutes;
    }
    
    /**
     * isApiRoute
     *
     * @param  string $uri
     * @return bool
     */
    public function isApiRoute($uri)
    {
        return (bool) preg_match('/^api/', $uri);
    }


    /**
     * cacheApiRoutes
     *
     * @param  Illuminate\Routing\RouteCollection $routes
     * @return bool
     */
    public function cacheApiRoutes($routes)
    {  $this->apiRoutes = $routes;
    }

    
    /**
     * determine whether a single api route is auth protected
     *
     * @param  Illuminate\Routing\Route $route
     * @return bool
     *
     * @todo add tests for regular expressioned routes i.e. /api/user/{user}
     */
    public function apiRouteRequiresAuth( $route )
    {
        $this->getsJsonForAuthenticatedRoute($route);
        $this->getsErrorForUnauthenticatedRoute($route);
    }

    
    /**
     * Check for unauthenticated error or redirect when not authenticated
     * for all http request methods given a route.
     *
     * @param  Illuminate\Routing\Route $route
     */
    public function getsErrorForUnauthenticatedRoute( $route )
    {
        foreach ($this->HttpRequestMethods as $method)
        {  $this->getsErrorForUnauthenticatedRouteAndMethod( $route, $method );
        }

        return true;
    }

    /**
     * Check for unauthenticated error or redirect when not authenticated
     * given a route and http reuest method.
     *
     * @param  Illuminate\Routing\Route $route
     * @param  string $method
     */
    public function getsErrorForUnauthenticatedRouteAndMethod( $route, $method )
    {
        $response = $this->$method($route->uri());
        
         $validUnauthedResponses = $this->validResponseForUnauthenticated;
        $status = $response->getStatusCode();

        try {
            $this->assertContains( $status , $validUnauthedResponses);
        } catch (Exception $e) {
            $msg  = 'Unprotected API route ' . $route->uri . '.  Returned  ' . $status . '.  ';
            $msg .= 'When unathenticated api should return ' . implode(' or ', $validUnauthedResponses);
            echo ( $msg . "\n" );
        }

        return true;
    }
}
