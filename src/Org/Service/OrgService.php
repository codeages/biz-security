<?php

namespace Codeages\Biz\Org\Service;

interface OrgService
{
    public function createOrg($org);

    public function updateOrg($id, $org);

    public function deleteOrg($id);

    public function getOrg($id);

    public function searchOrgs($conditions, $orderBys, $start, $limit);

    public function countOrgs($conditions);
}
