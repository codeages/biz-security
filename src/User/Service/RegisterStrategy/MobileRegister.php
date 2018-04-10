<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

class MobileRegister extends AbstractRegister
{
    public function fillUnRegisterUser($unregistedUser)
    {
        $unregistedUser['mobile'] = $unregistedUser['login_name'];

        $unregistedUser['nickname'] = $unregistedUser['login_name'];
        $unregistedUser['email'] = $unregistedUser['login_name'].'@';
        $unregistedUser['username'] = $unregistedUser['login_name'];
        return $unregistedUser;
    }
}