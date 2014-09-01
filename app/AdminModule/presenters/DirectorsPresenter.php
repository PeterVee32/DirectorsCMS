<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model;

class DirectorsPresenter extends SecuredPresenter {

    public function renderDefault(){
        $this->template->directors = $this->em->getDao(Model\Director::getClassName())->findBy(array());
    }
    
    public function renderEdit($id){
        $director = $this->em->getDao(Model\Director::getClassName())->find($id);
        if(!is_object($director)){
            $this->redirect('Directors:default');
        }
    }
    
    protected function createComponentNewDirectorForm() {
        $form = new Nette\Application\UI\Form;
        $director = null;
        if(is_numeric($this->getParam("id"))){
            $director = $this->em->getDao(Model\Director::getClassName())->find($this->getParam('id'));
        }
        
        $form->addText('name')
                ->setDefaultValue(is_object($director) ? $director->getName() : null)
                ->setRequired();

        $form->addSubmit('submit', is_object($director) ? 'Upraviť' : 'Pridať');

        $form->onSuccess[] = is_object($director) ? $this->editDirector : $this->addDirector;
        return $form;
    }
    
    public function addDirector($form, $values) {
        $director = new Model\Director();
        $director->setName($values->name);
        $this->em->persist($director);
        $this->em->flush();
        
        $this->flashMessage('Režisér bol úspešne pridaný !', 'success');
        $this->redirect('Directors:default');
    }
    
    public function handleDeleteDirector($id){
        $director = $this->em->getDao(Model\Director::getClassName())->find($id);
        if(is_object($director)){
            $this->em->remove($director);
            $this->em->flush();
        }
        $this->redirect('Directors:default');
    }
    
    public function editDirector($form, $values) {
        $director = $this->em->getDao(Model\Director::getClassName())->find($this->getParam('id'));
        $director->setName($values->name);
        $this->em->flush();
        
        $this->flashMessage('Režisér bol úspešne upravený !', 'success');
        $this->redirect('Directors:default');
    }
}
