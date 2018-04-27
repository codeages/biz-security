<?php

namespace Codeages\Biz\Org\Dao\Impl;

use Codeages\Biz\Org\Dao\OrgDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class OrgDaoImpl extends GeneralDaoImpl implements OrgDao
{
    protected $table = 'biz_security_org';

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
