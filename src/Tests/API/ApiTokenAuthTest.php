<?php

namespace Blasher\Laratest\Tests\API;

use App\Factory;
use App\User;
use Blasher\Laratest\ApiAuthenticatable;
use Blasher\Laratest\ApiAuthTestInterface as ApiAuthTestInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response as Response;
use Exception;
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
     * Check for results when authenticated.
     *
     * @depends assertUserModelHasApiTokenProperty
     * @param  Illuminate\Routing\Route $route
     */
    public function getsJsonForAuthenticatedRoute( $route )
    {
        $user = $this->createApiUser();
        
        $route_uri = $route->uri().'?api_token='.$user->api_token;
        
        try {
        $response = $this->actingAs($user)
                         ->get( $route->uri())
                         ->assertStatus( Response::HTTP_OK )
                         ->seeJson( [] );
        }
        catch( Exception $e)
        {  echo "\n". 'Failed authentication for ' . $route->uri() . "\n";
        }
    }
 

}
