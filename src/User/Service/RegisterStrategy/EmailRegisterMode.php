<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

use Codeages\Biz\Framework\Service\Exception\InvalidArgumentException;

class EmailRegisterMode extends AbstractRegisterMode
{
    public function fillUnRegisterUser($unregistedUser)
    {
        $loginName = $unregistedUser['login_name'];
        if (!stripos($loginName, '@')) {
            throw new InvalidArgumentException('email is invalid.');
        }

        $unregistedUser['email'] = $loginName;
        $username = substr($unregistedUser['login_name'], 0, stripos($loginName, '@'));
        $unregistedUser['nickname'] = $username;

        return $unregistedUser;
    }
}