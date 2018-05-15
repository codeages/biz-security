<?php

namespace Codeages\Biz\Org\Service\Impl;

use Codeages\Biz\Org\Service\OrgService;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\Framework\Service\BaseService;

class OrgServiceImpl extends BaseService implements OrgService
{
    public function createOrg($org)
    {
        $org = ArrayToolkit::parts($org, array('name', 'code', 'parent_id'));
        if (!ArrayToolkit::requireds($org, array('name', 'code'))) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        $savedOrg = $this->getOrgDao()->create($org);
        $internalCode = $savedOrg['id'].'.';
        if (!empty($savedOrg['parent_id'])) {
            $parentOrg = $this->getOrg($savedOrg['parent_id']);
            $internalCode = $parentOrg['internal_code'].$internalCode;
        }

        return $this->getOrgDao()->update($savedOrg['id'], array(
            'internal_code' => $internalCode
        ));
    }

    public function updateOrg($id, $org)
    {
        unset($org['parent_id']);
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
