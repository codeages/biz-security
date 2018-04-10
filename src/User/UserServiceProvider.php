<?php

namespace Codeages\Biz\User;

use Codeages\Biz\User\Service\RegisterStrategy\EmailOrMobileRegister;
use Codeages\Biz\User\Service\RegisterStrategy\EmailRegister;
use Codeages\Biz\User\Service\RegisterStrategy\MobileRegister;
use Codeages\Biz\User\Service\RegisterStrategy\UsernameRegister;
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

        $biz['user.options.register_mode'] = 'username';

        $biz['user.options'] = $biz->factory(function () use ($biz) {
            return array(
                'register_mode' => $biz['user.options.register_mode'], // username, email, mobile
            );
        });

        $registerModes = array(
            'email' => EmailRegister::class,
            'mobile' => MobileRegister::class,
            'username' => UsernameRegister::class,
            'email_or_mobile' => EmailOrMobileRegister::class,
        );

        $modeKeys = array_keys($registerModes);
        foreach ($modeKeys as $registerMode) {
            $class = $registerModes[$registerMode];
            $biz['user_register_mode.'.$registerMode] = function () use ($biz, $class) {
                return new $class($biz);
            };
        }
    }

}
