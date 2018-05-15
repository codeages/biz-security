<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

use Codeages\Biz\Framework\Service\Exception\InvalidArgumentException;

class UsernameRegisterMode extends AbstractRegisterMode
{
    public function fillUnRegisterUser($unregistedUser)
    {
        $loginName = $unregistedUser['login_name'];
        if(preg_match("/^1[123456789]{1}\d{9}$/", $loginName)) {  
            throw new InvalidArgumentException('username is invalid.');
        }

        if (stripos($loginName, '@')) {
            throw new InvalidArgumentException('username is invalid.');
        }

        $unregistedUser['username'] = $loginName;
        $unregistedUser['nickname'] = $loginName;
        return $unregistedUser;
    }
}