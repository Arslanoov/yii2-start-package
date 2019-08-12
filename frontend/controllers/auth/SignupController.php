<?php

namespace frontend\controllers\auth;

use core\repositories\UserRepository;
use core\useCases\auth\SignupService;
use Yii;
use core\forms\auth\SignupForm;
use yii\base\Module;
use yii\web\Controller;
use DomainException;

class SignupController extends Controller
{
    private $service;
    private $users;

    public function __construct(string $id, Module $module, SignupService $service, UserRepository $users, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->users = $users;
    }

    public function actionRequest()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->signup($form);
                Yii::$app->session->setFlash('success', 'Проверьте свою почту для подтверждения регистрации');
                return $this->goHome();
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    public function actionConfirm($token)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        try {
            $this->service->confirm($token);
            Yii::$app->session->setFlash('success', 'Ваш аккаунт была подтвержден');
            return $this->redirect(['/auth/auth/login']);
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goHome();
    }
}