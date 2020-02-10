<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 22.03.18
 * Time: 19:51
 */

namespace shop\forms\manage\shop\product;


use yii\base\Model;

class AudioFilesForm extends Model
{


    public $file;


    public function rules()
    {
        return [

            [['file'],

                'file',
                'skipOnEmpty' => true,
                'extensions' => 'mp3',
                'maxSize' => 30000000,
               ],

        ];
    }


}