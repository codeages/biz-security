<?php

namespace Codeages\Biz\User\Dao\Impl;

use Codeages\Biz\User\Dao\UserDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class UserHasRoleDaoImpl extends GeneralDaoImpl implements UserDao
{
    protected $table = 'biz_security_user_has_role';

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