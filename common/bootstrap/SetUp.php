<?php

namespace common\bootstrap;

use store\dispatchers\DeferredEventDispatcher;
use store\dispatchers\SimpleEventDispatcher;
use store\entities\User\events\UserSignUpConfirmed;
use store\entities\User\events\UserSignUpRequested;
use store\listeners\User\UserSignUpConfirmedListener;
use store\listeners\User\UserSignupRequestedListener;
use store\useCases\ContactService;
use Symfony\Component\EventDispatcher\EventDispatcher;
use yii\base\BootstrapInterface;
use Yii;
use yii\di\Container;
use yii\mail\MailerInterface;
use yii\rbac\ManagerInterface;

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