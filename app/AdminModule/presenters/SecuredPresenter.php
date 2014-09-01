<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model;

class SecuredPresenter extends \App\Presenters\BasePresenter {

    public function startup() {
        parent::startup();
        if(!$this->getUser()->isLoggedIn()){
            $this->redirect('Login:default');
        }
    }
    
}
