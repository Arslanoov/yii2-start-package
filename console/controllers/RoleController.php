<?php

namespace console\controllers;

use store\useCases\manage\User\UserManageService;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class RoleController extends Controller
{
    private $service;

    public function __construct($id, $module, UserManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionAssign(): void
    {
        $username = $this->prompt('Имя пользователя:', ['required' => true]);
        $user = $this->findModel($username);
        $role = $this->select('Роль: ', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        $this->service->assignRole($user->id, $role);
        $this->stdout('Сделано!' . PHP_EOL);
    }

    private function findModel($username): User
    {
        if (!$model = User::findOne(['username' => $username])) {
            throw new Exception('Пользователь не найден');
        }
        return $model;
    }
}