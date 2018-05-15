<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

abstract class AbstractRegisterMode
{
    protected $biz;

    public function __construct($biz)
    {
        $this->biz = $biz;
    }

    protected function randomStr($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str;
    }

    protected function getUserDao()
    {
        return $this->biz->dao('User:UserDao');
    }

    public function loadUserByLoginName($loginName)
    {
        if (stripos($loginName, '@') > 0) {
            return $this->getUserDao()->getByEmail($loginName);
        }

        if (preg_match("/^1[1234567890]{1}\d{9}$/",$loginName)) {
            return $this->getUserDao()->getByMobile($loginName);
        }

        return $this->getUserDao()->getByUsername($loginName);
    }

    abstract public function fillUnRegisterUser($unregistedUser);
}