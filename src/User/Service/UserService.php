<?php

namespace Codeages\Biz\User\Service;

interface UserService
{
    /**
     * 1、频率限制
     * @param $user
     * @return mixed
     */
    public function register($user);

    public function bindUser($bind);

    public function isValidLoginName($loginName);

    public function renameNickname($userId, $nickname);

    public function renameUsername($userId, $username);

    public function unbindUser($type, $bindId);

    public function changePassword($userId, $newPassword, $oldPassword);

    public function login($username, $password);

    public function lockUser($userId);

    public function unlockUser($userId);

    public function verifyEmail($userId);

    public function verifyMobile($userId);
}
