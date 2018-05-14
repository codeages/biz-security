<?php

namespace Codeages\Biz\User\Service\SecureLevel;

use Codeages\Biz\Framework\Service\Exception\InvalidArgumentException;

class LowLevel extends AbstractSecureLevel 
{
    protected function checkEmailMode($user)
    {
        if (!$this->verifyCaptcha($user)) {
            throw new InvalidArgumentException('captcha is invalid.');
        }
    }

    protected function checkMobileMode($user)
    {
        if (!$this->verifyCaptcha($user)) {
            throw new InvalidArgumentException('captcha is invalid.');
        }
    }

    protected function checkEmailOrMobileMode($user)
    {
        if (!$this->verifyCaptcha($user)) {
            throw new InvalidArgumentException('captcha is invalid.');
        }
    }

    protected function verifyCaptcha($user)
    {
        if (empty($user['captcha'])) {
            return false;
        }

        $captcha = $user['captcha'];

        $token = $this->getTokenService()->verify('user.register', $captcha['key']);
        if (!empty($token['data']['captcha']) && $captcha['data'] == $token['data']['captcha']) {
            return true;
        }
        return false;
    }

    protected function getTokenService()
    {
        return $this->biz->service('Token:TokenService');
    }
}
