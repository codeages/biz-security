<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

class UsernameRegister extends AbstractRegister
{
    public function fillUnRegisterUser($unregistedUser)
    {
        $unregistedUser['username'] = $unregistedUser['login_name'];

        $unregistedUser['nickname'] = $unregistedUser['login_name'];
        $unregistedUser['email'] = $unregistedUser['login_name'].'@';
        $unregistedUser['mobile'] = $unregistedUser['login_name'];
        return $unregistedUser;
    }
}