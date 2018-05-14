<?php

namespace Codeages\Biz\User\Service\SecureLevel;

use Codeages\Biz\Framework\Service\Exception\InvalidArgumentException;

class MiddleLevel extends AbstractSecureLevel 
{
    protected function checkEmailMode($user)
    {
        $this->lowLevelCheck($user);

        if (!$this->checkRegistedUsers($user)) {
            throw new InvalidArgumentException("rate limiter is pool, can't create user");
        }
    }

    protected function checkMobileMode($user)
    {
        $this->lowLevelCheck($user);

        if (!$this->checkRegistedUsers($user)) {
            throw new InvalidArgumentException("rate limiter is pool, can't create user");
        }
    }

    protected function checkEmailOrMobileMode($user)
    {
        $this->lowLevelCheck($user);

        if (!$this->checkRegistedUsers($user)) {
            throw new InvalidArgumentException("rate limiter is pool, can't create user");
        }
    }

    protected function lowLevelCheck($user)
    {
        $lowLevel = $this->biz['user_register_secure_level.low'];
        $lowLevel->check($user);
    }

    protected function checkRegistedUsers($user)
    {
        $condition = array(
            'created_time_LT' => time() - 24 * 3600,
            'created_ip' => $user['created_ip'],
        );
        $registerCount = $this->getUserService()->countUsers($condition);

        if ($registerCount >= 30) {
            return false;
        }

        return true;
    }
}
