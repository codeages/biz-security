<?php

namespace Codeages\Biz\Org\Service\Impl;

use Codeages\Biz\Org\Service\OrgService;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\Framework\Service\BaseService;

class OrgServiceImpl extends BaseService implements OrgService
{
    public function createOrg($org)
    {
        $org = ArrayToolkit::parts($org, array('name', 'code'));
        if (!ArrayToolkit::requireds($org, array('name', 'code'))) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }
        return $this->getOrgDao()->create($org);
    }

    public function updateOrg($id, $org)
    {
        return $this->getOrgDao()->update($id, $org);
    }

    public function deleteOrg($id)
    {
        return $this->getOrgDao()->delete($id);
    }

    public function getOrg($id)
    {
        return $this->getOrgDao()->get($id);
    }

    public function searchOrgs($conditions, $orderBys, $start, $limit)
    {
        return $this->getOrgDao()->search($conditions, $orderBys, $start, $limit);
    }

    public function countOrgs($conditions)
    {
        return $this->getOrgDao()->count($conditions);
    }

    protected function getOrgDao()
    {
        return $this->biz->dao('Org:OrgDao');
    }
}
