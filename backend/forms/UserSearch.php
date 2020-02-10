<?php

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\user\User;
use yii\helpers\ArrayHelper;

class UserSearch extends Model
{
    public $id;
    public $from_date;
    public $date_to;
    public $username;
    public $email;
    public $status;
    public $role;

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'email', 'role'], 'safe'],
            [['from_date',], 'date', 'format' => 'php:Y-m-d'],
            [['date_to',], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params)
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
            ->andFilterWhere(['<=', 'u.created_at', $this->from_date ? strtotime($this->from_date . ' 23:59:59') : null]);

        return $dataProvider;
    }




    public function rolesList()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}
