<?php

namespace common\bootstrap;

use store\useCases\ContactService;
use yii\base\BootstrapInterface;
use Yii;
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
    }
}