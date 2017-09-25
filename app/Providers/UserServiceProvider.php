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

        if ($client) {
            $client->post('/pw/index.cfm', [
                'form_params' => [
                    'DistrictCode' => session('district'),
                    'username' => $credentials['username'],
                    'password' => $credentials['password'],
                    'UserType' => $credentials['role'],
                    'login' => 'Login'
                ],
                'cookies' => session('jar')
            ]);
        } else {
            return false;
        }
    }
}
