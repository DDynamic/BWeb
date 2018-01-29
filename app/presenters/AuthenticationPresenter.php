<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;

class AuthenticationPresenter extends BasePresenter
{
    protected function startup()
    {
        parent::startup();
        $user = $this->getUser();
    }

    protected function renderLogin()
    {
        if ($user->isLoggedIn()) {
            $this->redirect('Dashboard:home');
        }
    }

    protected function createComponentLoginForm()
    {
        $form = new UI\Form;
        $form->addText('district', 'District Code')->setRequired('The district field is required.');
        $form->addText('username', 'Username')->setRequired('The username field is required.');
        $form->addPassword('password', 'Password')->setRequired('The password field is required.');
        $form->addRadioList('account', 'Account Type', ['PARENTSWEB-STUDENT' => 'Student', 'PARENTSWEB-PARENT' => 'Parent'])->setRequired('Please select an account type.');
        $form->addSubmit('submit');
        $form->onSuccess[] = [$this, 'loginForm'];
        return $form;
    }

    public function loginForm(UI\Form $form, $values)
    {
        // login to renweb
        // if valid add to accounts array then $this->user->login
        // else redirect back with invalid account details

        /*
        $accounts = ['username' => 'password'];
        $authenticator = new Nette\Security\SimpleAuthenticator($accounts);

        $user = $this->getUser();
        $user->setAuthenticator($authenticator);

        try {
            $this->user->login($values->account, $values->password);
            $this->redirect('Dashboard:home');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Invalid account details.');
        }
        */
    }

    public function actionLogout()
    {
        $this->getUser()->logout();
        $this->redirect('Authentication:login');
    }
}
