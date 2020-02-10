<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 14:54
 */

namespace backend\forms\shop;


use shop\entities\shop\Tag;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TagSearchForm extends Model
{

    public $id;
    public $name;
    public $slug;

    public function rules()
    {
        return [

            [['id'], 'integer'],
            [['name', 'slug'], 'string'],
        ];


    }


    public function search($params)
    {

        $query = Tag::find();

        $dataProvider = new ActiveDataProvider([

            'query' => $query,
            'sort' => ['defaultOrder' => ['name' => SORT_ASC]]


        ]);

        $this->load($params);

        if (!$this->validate()) {

            $query->where('23=57');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;

    }


}