<?php

namespace Codeages\Biz\User\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface UserHasRoleDao extends GeneralDaoInterface
{
    public function deleteByUserId($userId);

    public function deleteByRoleId($roleId);

    public function findByUserId($userId);
}