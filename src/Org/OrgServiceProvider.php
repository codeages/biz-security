<?php

namespace Codeages\Biz\Org;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class OrgServiceProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
        $biz['migration.directories'][] = dirname(dirname(__DIR__)).'/migrations/org';
        $biz['autoload.aliases']['Org'] = 'Codeages\Biz\Org';

        $biz['console.commands'][] = function () use ($biz) {
            return new \Codeages\Biz\Org\Command\TableCommand($biz);
        };
    }
}
