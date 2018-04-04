<?php

namespace Codeages\Biz\User\Service;

interface UserService
{
    public function register($user);

    public function changePassword($user);

    public function login($username, $password);

    public function lockUser($userId);

    public function unlockUser($userId);

    public function verifyEmail($userId);

    public function verifyMobile($userId);
}