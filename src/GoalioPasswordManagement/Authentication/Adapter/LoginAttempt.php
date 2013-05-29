<?php

namespace GoalioPasswordManagement\Authentication\Adapter;

use Zend\Authentication\Result as AuthenticationResult;
use GoalioPasswordManagement\Options\ModuleOptions;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter\AbstractAdapter;
use ZfcUser\Authentication\Adapter\AdapterChainEvent;

class LoginAttempt extends AbstractAdapter implements ServiceLocatorAwareInterface {

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @var \GoalioSecurity\Mapper\LoginAttempt
     */
    protected $loginAttemptMapper = null;

    /**
     * @var ModuleOptions
     */
    protected $options = null;

    public function authenticate(AdapterChainEvent $e) {

        $mapper = $this->getLoginAttemptMapper();
        $class = $this->getOptions()->getLoginAttemptEntityClass();

        $identity = $e->getRequest()->getPost()->get('identity');

        $loginAttempt = new $class;
        $loginAttempt->setRequestTime(new \DateTime());
        $loginAttempt->setIpAddress($_SERVER['REMOTE_ADDR']);
        $loginAttempt->setCredential($identity);
        $loginAttempt->setCode($e->getCode());

        $mapper->insertLoginAttempt($loginAttempt);

        $failed = $mapper->findFailedAttemptsByCredential($identity);

        if(count($failed) > $this->getOptions()->getDelayLoginAfterFailedAttempts()) {
            $e->setCode(AuthenticationResult::FAILURE_UNCATEGORIZED);
            $e->setMessages(array('overwrite' => 'Please request a new password or try again later.'));
        }

        return false;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    /**
     * @param null $loginAttemptsMapper
     *
     * @return $this
     */
    public function setLoginAttemptMapper($loginAttemptsMapper) {
        $this->loginAttemptMapper = $loginAttemptsMapper;
        return $this;
    }

    /**
     * @return null
     */
    public function getLoginAttemptMapper() {
        if ($this->loginAttemptMapper === null) {
            $this->loginAttemptMapper = $this->getServiceLocator()->get('goaliopasswordmanagement_loginattempt_mapper');
        }
        return $this->loginAttemptMapper;
    }

    /**
     * @param $options
     *
     * @return $this
     */
    public function setOptions($options) {
        $this->options = $options;

        return $this;
    }

    /**
     * @return \GoalioPasswordManagement\Options\ModuleOptions
     */
    public function getOptions() {
        if ($this->options === null) {
            $this->setOptions($this->getServiceLocator()->get('goaliopasswordmanagement_module_options'));
        }
        return $this->options;
    }

}