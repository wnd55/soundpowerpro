<?php
/**
 * Created by PhpStorm.
 * User: MaximIV
 * Date: 18.03.2018
 * Time: 16:45
 */

namespace backend\forms\shop;

use shop\entities\shop\Brand;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BrandSearchForm extends Model
{
    public $id;
    public $name;
    public $slug;

    public function rules()
    {
        return [

            [['id'], 'integer'],
           [['name','slug'], 'string'],
        ];


    }

    public function search($params)
    {

        $query = Brand::find();

        $dataProvider = new ActiveDataProvider([

            'query'=>$query,
            'sort'=>[ 'defaultOrder' => ['name' => SORT_ASC]]
        ]);

        $this->load($params);

        if(!$this->validate()){

            $query->where('23=57');
            return $dataProvider;
        }

        $query->andFilterWhere(['id'=> $this->id]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }

}