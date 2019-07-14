<?php

use store\helpers\UserHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model store\entities\User\User */

$this->title = 'Просмотр пользователя №' . $model->id;

$this->params['breadcrumbs'][] = [
    'label' => 'Пользователи',
    'url' => ['index']
];
$this->params['breadcrumbs'][] = 'Просмотр пользователя';

?>

<div class="user-view">

    <h1>Просмотр пользователя</h1>

    <p>
        <?php if ($model->isActive()): ?>
            <?= Html::a('Деактивировать', ['draft', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы действтельно хотите деактивировать этого пользователя?',
                    'method' => 'post',
                ]
            ])
            ?>
        <?php else: ?>
            <?= Html::a('Активировать', ['activate', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Вы действтельно хотите активировать этого пользователя?',
                    'method' => 'post',
                ]
            ]) ?>
        <?php endif; ?>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действтельно хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'created_at:datetime',
                    'updated_at:datetime',
                    'username',
                    'email:email',
                    [
                        'attribute' => 'status',
                        'value' => UserHelper::statusLabel($model->status),
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>
