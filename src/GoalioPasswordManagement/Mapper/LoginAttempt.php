<?php

namespace GoalioPasswordManagement\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class LoginAttempt extends AbstractDbMapper
{
    protected $tableName  = 'user_login_attempts';

}