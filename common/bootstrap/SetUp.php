<?php

namespace common\bootstrap;

use core\dispatchers\DeferredEventDispatcher;
use core\dispatchers\EventDispatcher;
use core\listeners\User\UserSignUpRequestedListener;
use core\useCases\ContactService;
use core\dispatchers\SimpleEventDispatcher;
use core\listeners\User\UserSignUpConfirmedListener;
use Yii;
use yii\base\BootstrapInterface;
use yii\di\Container;
use yii\mail\MailerInterface;
use yii\rbac\ManagerInterface;
use core\entities\User\events\UserSignUpRequested;
use core\entities\User\events\UserSignUpConfirmed;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;
        
        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });
        
        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });
        
        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail']
        ]);
        
        $container->setSingleton(EventDispatcher::class, DeferredEventDispatcher::class);
        $container->setSingleton(DeferredEventDispatcher::class, function (Container $container) {
            return new DeferredEventDispatcher(new SimpleEventDispatcher($container, [
                UserSignUpRequested::class => [UserSignupRequestedListener::class],
                UserSignUpConfirmed::class => [UserSignupConfirmedListener::class],
            ]));
        });

        return new SimpleEventDispatcher($container, [
            UserSignUpRequested::class => [UserSignupRequestedListener::class],
            UserSignUpConfirmed::class => [UserSignupConfirmedListener::class],
        ]);
    }
}