<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

class EmailOrMobileRegisterMode extends AbstractRegisterMode
{
    public function fillUnRegisterUser($unregistedUser)
    {
        if (stripos($loginName, '@')) {
            $registerMode = $this->biz['user_register_mode.email'];
            return $registerMode->fillUnRegisterUser($unregistedUser);
        } elseif (preg_match("/^1[1234567890]{1}\d{9}$/",$loginName)) {
            $registerMode = $this->biz['user_register_mode.mobile'];
            return $registerMode->fillUnRegisterUser($unregistedUser);
        } else {
            return $unregistedUser;
        }
    }
}
