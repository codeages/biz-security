<?php

namespace Codeages\Biz\Org\Service\Impl;

use Codeages\Biz\Org\Service\OrgService;

class OrgServiceImpl implements OrgService
{
    public function createOrg($org)
    {
    	return $this->getOrgDao()->create($org);
    }

    public function updateOrg($id, $org)
    {

    }

    public function deleteOrg($id)
    {

    }

    public function getOrg($id)
    {

    }

    public function searchOrgs($conditions, $orderBys, $start, $limit)
    {

    }

    public function countOrgs($conditions)
    {

    }

    protected function getOrgDao()
    {
    	return $this->biz->dao('Org:OrgDao');
    }
}
