<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use App\Models\Authenticator;

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
        $user = $this->getUser();
        $user->setAuthenticator(new Authenticator());

        try {
            $user->login($values->username, $values->password, $values->account, $values->district);
            $this->redirect('Dashboard:home');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Invalid credentials.');
        }
    }

    public function actionLogout()
    {
        $this->getUser()->logout();
        $this->redirect('Authentication:login');
    }
}
