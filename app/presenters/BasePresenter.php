<?php

namespace App\Presenters;

use Nette,
    App\Model;

abstract class BasePresenter extends Nette\Application\UI\Presenter {

    /* var \Kdyby\Doctrine\EntityManager */
    public $em;
    
    public function injectEntityManager(\Kdyby\Doctrine\EntityManager $em) {
        if ($this->em) {
            throw new \Nette\InvalidStateException('Entity manager has already been set');
        }
        $this->em = $em;
        return $this;
    }
    
    public function beforeRender() {
        parent::beforeRender();
        $this->template->loginUser = $this->getLoginUser();
    }

    public function getLoginUser(){
        if($this->getUser()->isLoggedIn()){
            return $this->em->getDao(Model\User::getClassName())->find($this->getUser()->getId());
        } else {
            return null;
        }
    }
    
    public function invalidSubmit($form) {
        foreach ($form->errors as $k => $error) {
            $this->presenter->flashMessage($error, 'error');
        }
        $form->cleanErrors();
    }
    
    public function handleLogout() {
        $this->getUser()->logout();
        $this->redirect('Login:default');
    }
}
