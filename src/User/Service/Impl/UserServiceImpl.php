<?php

namespace Codeages\Biz\User\Service\Impl;

use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\User\Service\UserService;
use Codeages\Biz\Framework\Service\BaseService;

class UserServiceImpl extends BaseService implements UserService
{
    public function register($user, $bind = array())
    {
        $userFields = array('login_name', 'password', 'created_ip', 'created_source');
        $unregistedUser = ArrayToolkit::parts($user, $userFields);

        if (!ArrayToolkit::requireds($unregistedUser, $userFields)) {
            throw $this->createInvalidArgumentException('user args is invalid.');
        }

        if (!empty($bind) && !ArrayToolkit::requireds($bind, array('type', 'type_alias', 'bind_id'))) {
            throw $this->createInvalidArgumentException('user bind args is invalid.');
        }

        $registedUser = array();

        $this->biz['lock']->get('user_register');
        try {
            $this->beginTransaction();

            $unregistedUser = $this->fillPassword($unregistedUser);
            $unregistedUser = $this->fillLoginName($unregistedUser);

            $registedUser = $this->getUserDao()->create($unregistedUser);
            if (!empty($bind)) {
                $bindUser = $this->bindUser($registedUser, $bind);
                $registedUser['bind'] = $bindUser;
            }
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            var_dump($e->getMessage());
        }
        $this->biz['lock']->release('user_register');

        $wrappedUser = $this->wrapUser($registedUser);
        $this->dispatch('user.register', $wrappedUser);

        return $wrappedUser;
    }

    protected function bindUser($registedUser, $bind)
    {
        $bind['user_id'] = $registedUser['id'];
        return $this->getUserBindDao()->create($bind);
    }

    protected function wrapUser($user)
    {
        unset($user['password']);
        unset($user['salt']);
        return $user;
    }

    protected function fillLoginName($unregistedUser)
    {
        $userOptions = $this->biz['user.options'];
        $registerMode = $userOptions['register_mode'];

        $registerStrategy = $this->biz['user_register_mode.'.$registerMode];
        $unregistedUser = $registerStrategy->fillUnRegisterUser($unregistedUser);

        if (!ArrayToolkit::requireds($unregistedUser, array($registerMode))) {
            throw $this->createInvalidArgumentException($registerMode.' is required.');
        }

        unset($unregistedUser['login_name']);
        return $unregistedUser;
    }

    protected function fillPassword($unregistedUser)
    {
        if (empty($unregistedUser['password'])) {
            $unregistedUser['password'] = $this->randomStr(32);
        }

        $unregistedUser['salt'] = $this->randomStr(32);
//        $unregistedUser['password'] = $this->getPasswordEncoder()->encodePassword($unregistedUser['password'], $unregistedUser['salt']);

        return $unregistedUser;
    }

    protected function getPasswordEncoder()
    {
        return new PasswordEncoder('sha256');
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

    protected function getUserBindDao()
    {
        return $this->biz->dao('User:UserBindDao');
    }
}