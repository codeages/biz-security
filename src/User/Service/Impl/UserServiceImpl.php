<?php

namespace Codeages\Biz\User\Service\Impl;

use Codeages\Biz\Framework\Service\Exception\ServiceException;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\User\Service\UserService;
use Codeages\Biz\Framework\Service\BaseService;

class UserServiceImpl extends BaseService implements UserService
{
    public function register($user)
    {
        $userFields = array('login_name', 'password', 'created_ip', 'created_source');
        $unregistedUser = ArrayToolkit::parts($user, $userFields);

        if (!ArrayToolkit::requireds($unregistedUser, $userFields)) {
            throw $this->createInvalidArgumentException('user args is invalid.');
        }

        $registedUser = array();

        $lockKey = 'user_register.'.$unregistedUser['login_name'];
        $this->biz['lock']->get($lockKey);
        try {
            $this->beginTransaction();

            $existUser = $this->getRegisterStrategy()->loadUserByLoginName($unregistedUser['login_name']);
            if (!empty($existUser)) {
                throw $this->createInvalidArgumentException('user is exist.');
            }

            $unregistedUser = $this->fillPassword($unregistedUser);
            $unregistedUser = $this->fillLoginName($unregistedUser);

            $registedUser = $this->getUserDao()->create($unregistedUser);
            
            $this->commit();
        } catch (ServiceException $e) {
            $this->rollback();
        }
        $this->biz['lock']->release($lockKey);

        $wrappedUser = $this->wrapUser($registedUser);
        $this->dispatch('user.register', $wrappedUser);

        return $wrappedUser;
    }

    public function bindUser($bind)
    {
        if (!empty($bind) && !ArrayToolkit::requireds($bind, array('type', 'type_alias', 'bind_id'))) {
            throw $this->createInvalidArgumentException('user bind args is invalid.');
        }

        $bindedUser = $this->getUserBindDao()->getByTypeAndBindId($bind['type'], $bind['bind_id']);
        if (!empty($bindedUser)) {
            throw $this->createInvalidArgumentException('user bind args is invalid.');
        }

        $registedUser = $this->register($bind);

        $bind = ArrayToolkit::parts($bind, array('type', 'type_alias', 'bind_id'));
        $bind['user_id'] = $registedUser['id'];

        $savedBind = $this->getUserBindDao()->create($bind);
        $registedUser['bind'] = $savedBind;
        return $registedUser;
    }

    public function unbindUser($type, $bindId)
    {
        $bindedUser = $this->getUserBindDao()->getByTypeAndBindId($type, $bindId);
        if (empty($bindedUser)) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        $this->getUserBindDao()->deleteByTypeAndBindId($type, $bindId);
    }

    protected function wrapUser($user)
    {
        unset($user['password']);
        unset($user['salt']);
        return $user;
    }

    protected function fillLoginName($unregistedUser)
    {
        $registerStrategy = $this->getRegisterStrategy();
        $unregistedUser = $registerStrategy->fillUnRegisterUser($unregistedUser);

        unset($unregistedUser['login_name']);
        return $unregistedUser;
    }

    protected function getRegisterStrategy()
    {
        $userOptions = $this->biz['user.options'];
        $registerMode = $userOptions['register_mode'];

        return $this->biz['user_register_mode.'.$registerMode];
    }

    protected function fillPassword($unregistedUser)
    {
        if (empty($unregistedUser['password'])) {
            $unregistedUser['password'] = $this->randomStr(32);
        }

        $unregistedUser['salt'] = $this->randomStr(32);
        $unregistedUser['password'] = $this->getPasswordEncoder()->encodePassword($unregistedUser['password'], $unregistedUser['salt']);
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
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str;
    }

    public function changePassword($userId, $newPassword, $oldPassword)
    {
        $user = $this->getUserDao()->get($userId);
        if (empty($user)) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        $oldPassword = $this->getPasswordEncoder()->encodePassword($oldPassword, $user['salt']);
        if ($oldPassword != $user['password']) {
            throw $this->createInvalidArgumentException('password is invalid.');
        }

        $salt = $this->randomStr(32);
        $newPassword = $this->getPasswordEncoder()->encodePassword($newPassword, $salt);

        $savedUser = $this->getUserDao()->update($user['id'], array(
            'salt' => $salt,
            'password' => $newPassword,
        ));

        return $this->wrapUser($savedUser);
    }

    public function login($username, $password)
    {
        // TODO: Implement login() method.
    }

    public function isValidLoginName($loginName)
    {

    }

    public function renameNickname($userId, $nickname)
    {
        $user = $this->getUserDao()->get($userId);
        if (empty($user)) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        $savedUser = $this->getUserDao()->update($user['id'], array(
            'nickname' => $nickname,
        ));

        return $this->wrapUser($savedUser);
    }

    public function renameUsername($userId, $username)
    {
        $user = $this->getUserDao()->get($userId);
        if (empty($user)) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        $exsitUser = $this->getUserDao()->getByUsername($username);
        if (!empty($exsitUser)) {
            throw $this->createInvalidArgumentException('username is exsit.');
        }

        $savedUser = $this->getUserDao()->update($user['id'], array(
            'username' => $username,
        ));

        return $this->wrapUser($savedUser);
    }

    public function lockUser($userId)
    {
        $user = $this->getUserDao()->get($userId);
        if (empty($user)) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        if ($user['locked']) {
            throw $this->createInvalidArgumentException('user is locked.');
        }

        $savedUser = $this->getUserDao()->update($user['id'], array(
            'locked' => 1,
        ));

        return $this->wrapUser($savedUser);
    }

    public function unlockUser($userId)
    {
        $user = $this->getUserDao()->get($userId);
        if (empty($user)) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        if (empty($user['locked'])) {
            throw $this->createInvalidArgumentException('user is unlocked.');
        }

        $savedUser = $this->getUserDao()->update($user['id'], array(
            'locked' => 0,
        ));

        return $this->wrapUser($savedUser);
    }

    public function verifyEmail($userId)
    {
        $user = $this->getUserDao()->get($userId);
        if (empty($user)) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        if (!empty($user['email_verified'])) {
            throw $this->createInvalidArgumentException('email is verifed.');
        }

        $savedUser = $this->getUserDao()->update($user['id'], array(
            'email_verified' => 1,
        ));

        return $this->wrapUser($savedUser);
    }

    public function verifyMobile($userId)
    {
        $user = $this->getUserDao()->get($userId);
        if (empty($user)) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        if (!empty($user['mobile_verified'])) {
            throw $this->createInvalidArgumentException('mobile is verifed.');
        }

        $savedUser = $this->getUserDao()->update($user['id'], array(
            'mobile_verified' => 1,
        ));

        return $this->wrapUser($savedUser);
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