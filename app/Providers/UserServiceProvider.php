<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;

use Helper;

class UserServiceProvider implements UserProvider
{
    public function retrieveById($identifier) {

    }

    public function retrieveByToken($identifier, $token) {

    }

    public function updateRememberToken(Authenticatable $user, $token) {
    }

    public function retrieveByCredentials(array $credentials) {
        return new User();
    }

    public function validateCredentials(Authenticatable $user, array $credentials) {
        session(['jar' =>  new \GuzzleHttp\Cookie\CookieJar()]);

        $client = Helper::client();
        $client->get('/pw/', ['cookies' => session('jar')]);

        $client->post('/pw/index.cfm', [
            'form_params' => [
                'DistrictCode' => $credentials['district'],
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'UserType' => $credentials['role'],
                'login' => 'Login'
            ],
            'cookies' => session('jar')
        ]);

        $response = $client->get('/pw/', ['cookies' => session('jar')]);

        if (strpos($response->getBody(), 'Logout') !== false) {
            session(['auth' => true]);
            return true;
        } else {
            return false;
        }
    }
}
