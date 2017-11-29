<?php

namespace Blasher\Laratest\Tests\API;

use App\User;
use Blasher\Laratest\ApiAuthenticatable;
use Blasher\Laratest\ApiAuthTestInterface as ApiAuthTestInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiPassportAuthTest extends TestCase implements ApiAuthTestInterface
{
    use ApiAuthenticatable;
    use RefreshDatabase;

    // PROPERTIES

    /**
     * Make api call with authentication.
     *
     * @depends assertUserModelHasApiTokenProperty
     *
     * @param  User $user
     * @param  Illuminate\Routing\Route $route
     * @param  string $method
     */
    public function makeApiCallWithAuthentication($user, $route, $method)
    {
        echo $user->name.' - '.$route->uri().' - '.$method."\n";
        $route_uri = $route->uri().'?api_token='.$user->api_token;

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/user', ['name' => 'Sally']);
    }
}
