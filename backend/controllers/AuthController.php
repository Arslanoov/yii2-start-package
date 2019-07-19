<?php

namespace backend\controllers;

use common\auth\Identity;
use store\forms\auth\LoginForm;
use store\useCases\auth\AuthService;
use yii\base\Module;
use yii\web\Controller;
use Yii;
use DomainException;

class AuthController extends Controller
{
    private $service;

    public function __construct(string $id, Module $module, AuthService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->auth($form);
                Yii::$app->user->login(new Identity($user), $form->rememberMe ? 3600 * 24 * 30 : 0);
                Yii::$app->session->setFlash('success', 'Вы успешно авторизировались');
                return $this->goHome();
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', 'Вы успешно вышли из системы');
        return $this->goHome();
    }
}