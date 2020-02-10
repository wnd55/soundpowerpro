<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.01.19
 * Time: 18:47
 */

namespace shop\forms\shop\search;


use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use yii\base\Model;
use yii\helpers\ArrayHelper;


class SearchForm extends Model
{

    public $text;
    public $category;
    public $brand;






    public function rules()
    {
        return [

            [['text',], 'string'],
            [['brand', 'category'], 'integer'],


        ];
    }


    /**
     * @return array
     */

    public function brandsList()
    {

        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');


    }


    public function categoriesList()
    {

        return ArrayHelper::map(
            Category::find()
                ->andWhere(['>', 'depth', 0])
                ->orderBy('lft')
                ->asArray()
                ->all(),
            'id', function ($category) {
            return ($category['depth'] > 1 ? str_repeat('--', $category['depth'] - 1) . '' : '') . $category['name'];
        });


    }


}