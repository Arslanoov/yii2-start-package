<?php

namespace common\bootstrap;

use store\entities\User\events\UserSignUpConfirmed;
use store\entities\User\events\UserSignUpRequested;
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

        $container->setSingleton(EventDispatcher::class, function (Container $container) {
            return new SimpleEventDispatcher([
                UserSignUpRequested::class => [
                    [$container->get(UserSignupRequestedListener::class), 'handle'],
                ],
                UserSignUpConfirmed::class => [
                    [$container->get(UserSignupConfirmedListener::class), 'handle'],
                ],
            ]);
        });
    }
}