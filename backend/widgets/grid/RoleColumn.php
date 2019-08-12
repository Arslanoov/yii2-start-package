<?php

namespace backend\widgets\grid;

use core\access\Rbac;
use Yii;
use yii\grid\DataColumn;
use yii\rbac\Item;
use yii\helpers\Html;

class RoleColumn extends DataColumn
{
    public $grid;

    protected function renderDataCellContent($model, $key, $index): string
    {
        $roles = Yii::$app->authManager->getRolesByUser($model->id);
        return $roles === [] ? $this->grid->emptyCell : implode(', ', array_map(function (Item $role) {
            return $this->getRoleLabel($role);
        }, $roles));
    }

    private function getRoleLabel(Item $role): string
    {
        switch ($role->name) {
            case Rbac::ROLE_USER:
                $class = 'primary';
                break;
            case Rbac::ROLE_MANAGER:
                $class = 'success';
                break;
            case Rbac::ROLE_CONTENT_MANAGER:
                $class = 'info';
                break;
            case Rbac::ROLE_ADMIN:
                $class = 'danger';
                break;
            default:
                $class = 'default';
        }

        return Html::tag('div', Html::encode($role->description), ['class' => 'label label-' . $class]);
    }
}