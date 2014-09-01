<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model;

class LoginPresenter extends \App\Presenters\BasePresenter {
    
    protected function createComponentLoginForm() {
        $form = new Nette\Application\UI\Form;
        $form->addText('username', 'Username:')->setRequired();

        $form->addPassword('password', 'Password:')->setRequired();

        $form->addCheckbox('remember');

        $form->addSubmit('submit', 'Prihlásiť');

        $form->onSuccess[] = $this->signInFormSucceeded;
        return $form;
    }

    public function signInFormSucceeded($form, $values) {
        if ($values->remember) {
            $this->getUser()->setExpiration('14 days', FALSE);
        } else {
            $this->getUser()->setExpiration('20 minutes', TRUE);
        }

        try {
            $this->getUser()->login($values->username, $values->password);
            $this->redirect('Homepage:');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }

}
