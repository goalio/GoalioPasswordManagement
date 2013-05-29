<?php

namespace GoalioPasswordManagement\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $loginPasswordChangeEntityClass = 'GoalioPasswordManagement\Entity\LoginPasswordChange';

    /**
     * @var string
     */
    protected $loginAttemptEntityClass = 'GoalioPasswordManagement\Entity\LoginAttempt';

    /**
     * @var int
     */
    protected $checkLoginPeriod = 3600;

    /**
     * @var int|null
     */
    protected $delayLoginAfterFailedAttempts = 3;

    /**
     * @var int|null
     */
    protected $blockLoginAfterFailedAttempts = null;

    /**
     * @param int|null $blockLoginAfterFailedAttempts
     *
     * @return $this
     */
    public function setBlockLoginAfterFailedAttempts($blockLoginAfterFailedAttempts) {
        $this->blockLoginAfterFailedAttempts = $blockLoginAfterFailedAttempts;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBlockLoginAfterFailedAttempts() {
        return $this->blockLoginAfterFailedAttempts;
    }

    /**
     * @param int $checkLoginPeriod
     *
     * @return $this
     */
    public function setCheckLoginPeriod($checkLoginPeriod) {
        $this->checkLoginPeriod = $checkLoginPeriod;

        return $this;
    }

    /**
     * @return int
     */
    public function getCheckLoginPeriod() {
        return $this->checkLoginPeriod;
    }

    /**
     * @param int|null $delayLoginAfterFailedAttempts
     *
     * @return $this
     */
    public function setDelayLoginAfterFailedAttempts($delayLoginAfterFailedAttempts) {
        $this->delayLoginAfterFailedAttempts = $delayLoginAfterFailedAttempts;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDelayLoginAfterFailedAttempts() {
        return $this->delayLoginAfterFailedAttempts;
    }

    /**
     * @param string $loginAttemptEntityClass
     *
     * @return $this
     */
    public function setLoginAttemptEntityClass($loginAttemptEntityClass) {
        $this->loginAttemptEntityClass = $loginAttemptEntityClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getLoginAttemptEntityClass() {
        return $this->loginAttemptEntityClass;
    }

    /**
     * @param string $loginPasswordChangeEntityClass
     *
     * @return $this
     */
    public function setLoginPasswordChangeEntityClass($loginPasswordChangeEntityClass) {
        $this->loginPasswordChangeEntityClass = $loginPasswordChangeEntityClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getLoginPasswordChangeEntityClass() {
        return $this->loginPasswordChangeEntityClass;
    }



}
