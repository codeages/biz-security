<?php

namespace Codeages\Biz\User\Subscriber;

use Codeages\Biz\Framework\Event\Event;
use Codeages\Biz\Framework\Event\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber extends EventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'role.deleted' => 'onRoleDeleted'
        );
    }

    public function onUserRegister(Event $event)
    {
        $role = $event->getSubject();
        $this->getUserHasRoleDao()->deleteByRoleId($role['id']);
    }

    protected function getUserHasRoleDao()
    {
        return $this->getBiz()->dao('User:UserHasRoleDao');
    }
}