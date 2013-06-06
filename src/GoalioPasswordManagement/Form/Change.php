<?php

namespace GoalioPasswordManagement\Form;

use GoalioPasswordManagement\Options\ModuleOptions;
use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

class Change extends ProvidesEventsForm
{

    protected $passwordOptions;

    public function __construct($name = null, ModuleOptions $passwordOptions)
    {
        $this->setPasswordOptions($passwordOptions);
        parent::__construct($name);

        $this->add(array(
            'name' => 'newCredential',
            'options' => array(
                'label' => 'New Password',
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'newCredentialVerify',
            'options' => array(
                'label' => 'Verify New Password',
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
            ->setLabel('Set new password')
            ->setAttributes(array(
                'type'  => 'submit',
            ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

        $this->getEventManager()->trigger('init', $this);
    }

    public function setPasswordOptions(ModuleOptions $passwordOptions)
    {
        $this->passwordOptions = $passwordOptions;
        return $this;
    }

    public function getPasswordOptions()
    {
        return $this->passwordOptions;
    }
}
