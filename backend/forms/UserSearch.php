<?php

namespace backend\forms;

use Yii;
use store\entities\User\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class UserSearch extends Model
{
    public $id;
    public $date_from;
    public $date_to;
    public $updated_from;
    public $updated_to;
    public $username;
    public $email;
    public $status;
    public $role;

    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'email', 'role'], 'safe'],
            [['date_from', 'date_to', 'updated_from', 'updated_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = User::find()->alias('u');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'u.id' => $this->id,
            'u.status' => $this->status,
        ]);

        if (!empty($this->role)) {
            $query->innerJoin('{{%auth_assignments}} a', 'a.user_id = u.id');
            $query->andWhere(['a.item_name' => $this->role]);
        }

        $query
            ->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['>=', 'u.created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'u.created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'u.updated_at', $this->updated_from ? strtotime($this->updated_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'u.updated_at', $this->updated_to ? strtotime($this->updated_to . ' 23:59:59') : null]);

        return $dataProvider;
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}