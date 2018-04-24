<?php

namespace Codeages\Biz\User\Dao\Impl;

use Codeages\Biz\User\Dao\UserHasRoleDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class UserHasRoleDaoImpl extends GeneralDaoImpl implements UserHasRoleDao
{
    protected $table = 'biz_security_user_has_role';

    public function deleteByUserId($userId)
    {
        return $this->db()->delete($this->table(), array('user_id' => $userId));
    }

    public function findByUserId($userId)
    {
        return $this->findByFields(array(
            'user_id' => $userId
        ));
    }

    public function declares()
    {
        return array(
            'timestamps' => array('created_time', 'updated_time'),
            'serializes' => array(
            ),
            'orderbys' => array(
                'id',
                'created_time',
            ),
            'conditions' => array(
            ),
        );
    }
}