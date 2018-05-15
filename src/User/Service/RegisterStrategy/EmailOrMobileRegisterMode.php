<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

class EmailOrMobileRegisterMode extends AbstractRegisterMode
{
    public function fillUnRegisterUser($unregistedUser)
    {
        if (stripos($loginName, '@')) {
            $unregistedUser['email'] = $unregistedUser['login_name'];
        } elseif (preg_match("/^1[1234567890]{1}\d{9}$/",$loginName)) {
            $unregistedUser['mobile'] = $unregistedUser['login_name'];
        } else {
        	$unregistedUser['username'] = $unregistedUser['login_name'];
        }

        return $unregistedUser;
    }
}