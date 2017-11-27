<?php

namespace Blasher\Laratest\Tests\API;

use App\Factory;
use App\User;
use Blasher\Laratest\ApiAuthenticatable;
use Blasher\Laratest\ApiAuthTestInterface as ApiAuthTestInterface;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response as Response;
use Request;
use Route;
use Schema;
use Tests\TestCase;

class ApiTokenAuthTest extends TestCase implements ApiAuthTestInterface
{
    use ApiAuthenticatable;
    use RefreshDatabase;

    // PROPERTIES
    
    protected $apiUser;
    protected $apiRoutes;

    /**
     * Assert User model has api_token property.
     *
     * @test
     */
    public function assertUserTableHasApiTokenProperty()
    {
        $user = new User;

        $this->assertTrue(Schema::hasColumn('users', 'api_token') );
    }

   
    /**
     * Assert User model has api_token property.
     *
     * @test
     * @depends assertUserTableHasApiTokenProperty
     */
    public function assertUserModelHasApiTokenProperty()
    {
        $user = new User;

        $fillable = $user->getFillable();
        $guarded = $user->getGuarded();

        $parms = $fillable + $guarded;
        
        $this->assertContains('api_token', $parms);
    }


    /**
     * Ensure User has api_token.
     *
     */
    public function ensureUserHasApiToken($user)
    {
        $api_token = $user->api_token;

        if(!($user->api_token))
        {
            $api_token = str_random(60);
            $user->api_token = $api_token;
            $user->save();
        }

        return $api_token;
    }


    /**
     * Make api call with authentication.
     *
     * @param   User $user
     * @param   Illuminate\Routing\Route $route
     * @param   string $method
     * @todo    more graceful approach to testing HEAD method
     */
    public function makeApiCallWithAuthentication( $user, $route, $method )
    {
        $api_token = $this->ensureUserHasApiToken($user);

        $method = str_replace('head', 'get', strtolower($method));

        //        $request = Request::create('/'.$route->uri(), strtoupper($method));
        //        $expected_response = Route::dispatch($request);
        //        dd($expected_response);
        
        $route_uri = $route->uri().'?api_token='.$api_token;

        //        echo "\n".'TESTING '.$user->name.' - '.$method.' - '.$route_uri."\n";
        
        $response = $this->actingAs($user)
                         ->$method( $route_uri );
        
        return($response);
    }
}
