<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;

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
        // use the $credentials array to return true or false
        // depending on valid credientials
        // TODO
    }
}
