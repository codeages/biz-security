<?php

namespace Codeages\Biz\User\Service\Impl;

use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\User\Service\UserService;
use Codeages\Biz\Framework\Service\BaseService;

class UserServiceImpl extends BaseService implements UserService
{
    public function register($user, $bind = array())
    {
        $unregistedUser = ArrayToolkit::parts($user, array('username', 'mobile', 'email', 'nickname', 'password', 'created_ip', 'created_source'));

        $unregistedUser = $this->fillLoginName($unregistedUser);
        $unregistedUser = $this->fillPassword($unregistedUser);
        $unregistedUser = $this->fillNickname($unregistedUser);
        return $this->swapUser($this->getUserDao()->create($unregistedUser));
    }

    protected function swapUser($user)
    {
        unset($user['password']);
        unset($user['salt']);
        return $user;
    }

    protected function fillNickname($unregistedUser)
    {
        if (empty($unregistedUser['nickname'])) {
            $unregistedUser['nickname'] = $this->randomStr(10);
        }
        return $unregistedUser;
    }

    protected function fillLoginName($unregistedUser)
    {
        $userOptions = $this->biz['user.options'];
        $registerType = $userOptions['register_type'];
        $registerTypes = array('username', 'mobile', 'email');
        if (!ArrayToolkit::requireds($unregistedUser, array($registerType))) {
            throw $this->createInvalidArgumentException($registerType.' is required.');
        }

        $registerTypes = array_diff($registerTypes, array($registerType));
        foreach ($registerTypes as $fillField) {
            if (empty($unregistedUser[$fillField])) {
                $unregistedUser[$fillField] = $this->randomStr(10);
            }
        }

        return $unregistedUser;
    }

    protected function fillPassword($unregistedUser)
    {
        if (empty($unregistedUser['password'])) {
            $unregistedUser['password'] = $this->randomStr(32);
        }

        $unregistedUser['salt'] = $this->randomStr(32);

        return $unregistedUser;
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

    public function changePassword($userId, $newPassword, $oldPassword)
    {
        // TODO: Implement changePassword() method.
    }

    public function login($username, $password)
    {
        // TODO: Implement login() method.
    }

    public function lockUser($userId)
    {
        // TODO: Implement lockUser() method.
    }

    public function unlockUser($userId)
    {
        // TODO: Implement unlockUser() method.
    }

    public function verifyEmail($userId)
    {
        // TODO: Implement verifyEmail() method.
    }

    public function verifyMobile($userId)
    {
        // TODO: Implement verifyMobile() method.
    }

    protected function getUserDao()
    {
        return $this->biz->dao('User:UserDao');
    }
}