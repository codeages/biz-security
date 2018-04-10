<?php

namespace Codeages\Biz\User\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface UserDao extends GeneralDaoInterface
{
    public function getByUsername($username);

    public function getByMobile($mobile);

    public function getByEmail($email);
}