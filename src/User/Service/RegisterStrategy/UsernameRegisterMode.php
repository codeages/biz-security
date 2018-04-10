<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

class UsernameRegisterMode extends AbstractRegisterMode
{
    public function fillUnRegisterUser($unregistedUser)
    {
        $unregistedUser['username'] = $unregistedUser['login_name'];

        $unregistedUser['nickname'] = $unregistedUser['login_name'];
        $unregistedUser['email'] = $unregistedUser['login_name'].'@';
        $unregistedUser['mobile'] = $unregistedUser['login_name'];
        return $unregistedUser;
    }

    public function loadUserByLoginName($loginName)
    {
        return $this->getUserDao()->getByUsername($loginName);
    }
}