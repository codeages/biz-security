<?php

namespace Codeages\Biz\Role;

use Codeages\Biz\User\Service\RegisterStrategy\EmailOrMobileRegisterMode;
use Codeages\Biz\User\Service\RegisterStrategy\EmailRegisterMode;
use Codeages\Biz\User\Service\RegisterStrategy\MobileRegisterMode;
use Codeages\Biz\User\Service\RegisterStrategy\UsernameRegisterMode;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RoleServiceProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
        $biz['migration.directories'][] = dirname(dirname(__DIR__)).'/migrations/role';
        $biz['autoload.aliases']['Role'] = 'Codeages\Biz\Role';


        $biz['console.commands'][] = function () use ($biz) {
            return new \Codeages\Biz\Role\Command\TableCommand($biz);
        };
    }
}
