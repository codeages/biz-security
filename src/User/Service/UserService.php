<?php

namespace Codeages\Biz\User\Service;

interface UserService
{
    /**
     * 1、频率限制
     * @param $user
     * @return mixed
     */
    public function register($user, $bind = array());

    public function changePassword($userId, $newPassword, $oldPassword);

    public function login($username, $password);

    public function lockUser($userId);

    public function unlockUser($userId);

    public function verifyEmail($userId);

    public function verifyMobile($userId);
}