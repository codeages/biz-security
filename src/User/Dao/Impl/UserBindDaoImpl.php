<?php

namespace Codeages\Biz\User\Dao\Impl;

use Codeages\Biz\User\Dao\UserBindDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class UserBindDaoImpl extends GeneralDaoImpl implements UserBindDao
{
    protected $table = 'biz_security_user_bind';

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