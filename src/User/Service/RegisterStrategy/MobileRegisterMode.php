<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

use Codeages\Biz\Framework\Service\Exception\InvalidArgumentException;

class MobileRegisterMode extends AbstractRegisterMode
{
    public function fillUnRegisterUser($unregistedUser)
    {
        $loginName = $unregistedUser['login_name'];
        if(!preg_match("/^1[123456789]{1}\d{9}$/", $loginName)) {  
            throw new InvalidArgumentException('mobile is invalid.');
        }

        $unregistedUser['mobile'] = $loginName;
        $unregistedUser['username'] = 'S'.$loginName;
        $unregistedUser['nickname'] = 'S'.$loginName;

        return $unregistedUser;
    }
}