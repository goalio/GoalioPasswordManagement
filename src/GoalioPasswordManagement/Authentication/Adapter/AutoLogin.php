<?php

namespace GoalioPasswordManagement\Authentication\Adapter;

use Zend\Authentication\Result as AuthenticationResult;
use GoalioPasswordManagement\Options\ModuleOptions;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter\AbstractAdapter;
use ZfcUser\Authentication\Adapter\AdapterChainEvent;
use ZfcUser\Mapper\User as UserMapperInterface;

class AutoLogin extends AbstractAdapter implements ServiceLocatorAwareInterface {

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @var UserMapperInterface
     */
    protected $mapper = null;

    /**
     * @var ModuleOptions
     */
    protected $options = null;

    public function authenticate(AdapterChainEvent $e) {

        $options = $this->getOptions();

        if($options->getAutoLogin() !== null) {
            $mapper = $this->getMapper();
            $userObject = $mapper->findById($options->getAutoLogin());

            // Success!
            $e->setIdentity($userObject->getId());
            $this->setSatisfied(true);
            $storage = $this->getStorage()->read();
            $storage['identity'] = $e->getIdentity();
            $this->getStorage()->write($storage);
            $e->setCode(AuthenticationResult::SUCCESS)
                ->setMessages(array('Authentication successful.'));
        }
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
     * getMapper
     *
     * @return UserMapperInterface
     */
    public function getMapper()
    {
        if (null === $this->mapper) {
            $this->mapper = $this->getServiceLocator()->get('zfcuser_user_mapper');
        }
        return $this->mapper;
    }

    /**
     * setMapper
     *
     * @param UserMapperInterface $mapper
     * @return Db
     */
    public function setMapper(UserMapperInterface $mapper)
    {
        $this->mapper = $mapper;
        return $this;
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