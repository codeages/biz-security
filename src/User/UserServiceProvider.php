<?php

namespace Codeages\Biz\User;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class UserServiceProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
        $biz['migration.directories'][] = dirname(dirname(__DIR__)).'/migrations/user';
        $biz['autoload.aliases']['User'] = 'Codeages\Biz\User';


        $biz['console.commands'][] = function () use ($biz) {
            return new \Codeages\Biz\User\Command\TableCommand($biz);
        };

        $biz['user.options.register_type'] = 'username';

        $biz['user.options'] = $biz->factory(function () use ($biz) {
            return array(
                'register_type' => $biz['user.options.register_type'], // username, email, mobile
            );
        });
    }

}
