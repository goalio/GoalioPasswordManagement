<?php

namespace GoalioPasswordManagement\Service;

use ZfcUser\Options\PasswordOptionsInterface;
use GoalioPasswordManagement\Mapper\PasswordChange;
use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcBase\EventManager\EventProvider;

class PasswordManagementService extends EventProvider implements ServiceLocatorAwareInterface {

    protected $zfcUserOptions;

    /**
     * @var PasswordChange
     */
    protected $passwordMapper;

    protected $serviceLocator;

    protected $userMapper;

    public function hasToChangePassword($identity) {
        return !!$this->getPasswordMapper()->findByUser($identity);
    }

    public function changePassword($password, $user, array $data)
    {
        $newPass = $data['newCredential'];

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->getZfcUserOptions()->getPasswordCost());

        $pass = $bcrypt->create($newPass);
        $user->setPassword($pass);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user));
        $this->getUserMapper()->update($user);
        $this->remove($password);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $user));

        return true;
    }



    public function remove($m)
    {
        return $this->getPasswordMapper()->remove($m);
    }

    /**
     * @param mixed $serviceLocator
     *
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }


    /**
     * @param \GoalioPasswordManagement\Mapper\PasswordChange $passwordMapper
     *
     * @return $this
     */
    public function setPasswordMapper($passwordMapper) {
        $this->passwordMapper = $passwordMapper;

        return $this;
    }

    /**
     * @return \GoalioPasswordManagement\Mapper\PasswordChange
     */
    public function getPasswordMapper() {
        return $this->passwordMapper;
    }


    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceLocator()->get('zfcuser_user_mapper');
        }
        return $this->userMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function setUserMapper(UserMapperInterface $userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }

    public function getZfcUserOptions()
    {
        if (!$this->zfcUserOptions instanceof PasswordOptionsInterface) {
            $this->setZfcUserOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->zfcUserOptions;
    }

    public function setZfcUserOptions(PasswordOptionsInterface $zfcUserOptions)
    {
        $this->zfcUserOptions = $zfcUserOptions;
        return $this;
    }



}