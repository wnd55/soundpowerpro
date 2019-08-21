<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.08.19
 * Time: 13:30
 */

namespace shop\forms\manage\shop\product;


use yii\base\Model;

class StemsForm extends  Model
{

    public $file;


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'file' => 'Stem',
            'status' => 'Статус',
            'sort' => 'Сортировка',
        ];
    }


    public function rules()
    {
        return [

            [['file'],

                'file',
                'skipOnEmpty' => true,
                'extensions' => 'mp3',

            ],

        ];
    }





}