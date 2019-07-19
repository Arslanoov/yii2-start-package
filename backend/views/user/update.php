<?php

/* @var $this yii\web\View */
/* @var $model store\forms\manage\User\UserEditForm */
/* @var $user store\entities\User\User */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;

$this->title = 'Обновление пользователя №' . $user->id;

$this->params['breadcrumbs'][] = [
    'label' => 'Пользователи',
    'url' => ['index']
];
$this->params['breadcrumbs'][] = 'Обновить';

?>

<div class="user-update">

    <h1>Обновление пользователя</h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'role')->dropDownList($model->rolesList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
