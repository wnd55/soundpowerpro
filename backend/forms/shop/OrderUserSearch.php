<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.02.19
 * Time: 22:02
 */

namespace backend\forms\shop;


use shop\entities\shop\order\OrderUser;
use shop\helpers\OrderHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrderUserSearch extends Model
{


    public $id;

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function search($params)
    {

        $query = OrderUser::find();


        $dataProvider = new ActiveDataProvider([

            'query' =>$query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        return $dataProvider;

    }

    public function statusUserList()
    {
        return OrderHelper::statusUserList();
    }







}