<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

class MobileRegisterMode extends AbstractRegisterMode
{
    public function fillUnRegisterUser($unregistedUser)
    {
        $unregistedUser['mobile'] = $unregistedUser['login_name'];

        $unregistedUser['nickname'] = $unregistedUser['login_name'];
        $unregistedUser['email'] = $unregistedUser['login_name'].'@';
        $unregistedUser['username'] = $unregistedUser['login_name'];
        return $unregistedUser;
    }

    public function loadUserByLoginName($loginName)
    {
        return $this->getUserDao()->getByMobile($loginName);
    }
}