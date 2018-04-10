<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

class EmailOrMobileRegister extends AbstractRegister
{
    public function fillUnRegisterUser($unregistedUser)
    {
        $unregistedUser['email'] = $unregistedUser['login_name'];
        return $unregistedUser;
    }
}