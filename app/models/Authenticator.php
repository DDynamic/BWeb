<?php

namespace App\Models;

use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\AuthenticationException;

use GuzzleHttp\Cookie\CookieJar;

use App\Containers\RenwebContainer;

class Authenticator implements IAuthenticator
{
    function authenticate(array $credentials)
    {
        list($username, $password, $account, $district) = $credentials;

        $container = new RenwebContainer(['district' => $district]);
        $client = $container->getService('renweb');
        $cookies = new CookieJar;

        $response = $client->post('/pw/index.cfm', [
            'form_params' => [
                'DistrictCode' => $district,
                'username' => $username,
                'password' =>$password,
                'UserType' => $account,
                'login' => 'Login'
            ],
            'cookies' => $cookies
        ]);

        $response = $client->get('/pw', [
            'cookies' => $cookies
        ])->getBody();

        if (strpos($response, 'Logout') !== false) {
            return new Identity(0, 'user', ['username' => $username, 'district' => $district, 'cookies' => $cookies]);
        } else {
            throw new AuthenticationException('Invalid credientals.');
        }
    }
}
