<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

class EmailOrMobileRegisterMode extends AbstractRegisterMode
{
    public function fillUnRegisterUser($unregistedUser)
    {
        $unregistedUser['email'] = $unregistedUser['login_name'];
        return $unregistedUser;
    }

    public function loadUserByLoginName($loginName)
    {

    }
}