<?php

namespace Codeages\Biz\User\Service\RegisterStrategy;

abstract class AbstractRegister
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

    abstract public function fillUnRegisterUser($unregistedUser);
}