<?php

namespace App\Models;

use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\AuthenticationException;

use App\Containers\RenwebContainer;

class Authenticator implements IAuthenticator
{
    function authenticate(array $credentials)
    {
        list($username, $password, $account, $district) = $credentials;

        $container = new RenwebContainer(['district' => $district]);
        $client = $container->getService('renweb');

        $response = $client->post('/pw/index.cfm', [
            'form_params' => [
                'DistrictCode' => $district,
                'username' => $username,
                'password' =>$password,
                'UserType' => $account,
                'login' => 'Login'
            ],
        ]);

        $response = $client->get('/pw')->getBody();

        if (strpos($response, 'Logout') !== false) {
            return new Identity(0, 'user', ['username' => $username]);
        } else {
            throw new AuthenticationException('Invalid credientals.');
        }
    }
}
