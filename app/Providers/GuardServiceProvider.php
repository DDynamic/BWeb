<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ConnectException;

use App\Models\User;

use Helper;

class GuardServiceProvider extends SessionGuard implements Guard
{
    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        if (session('jar') !== null) {
            $client = Helper::client();

            if ($client) {
                try {
                    $response = $client->get('/pw/', ['cookies' => session('jar')]);
                } catch (ConnectException $e) {
                    return false;
                }

                if (strpos($response->getBody(), 'Logout') !== false) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        if ($this->check) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return new User();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        //
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        //
    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        //
    }
}
