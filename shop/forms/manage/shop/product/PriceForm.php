<?php

namespace shop\forms\manage\shop\product;

use shop\entities\shop\product\product;
use yii\base\Model;


class PriceForm extends Model
{
    public $old;
    public $new;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->new = $product->price_new;
            $this->old = $product->price_old;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [

            [['old', 'new'], 'integer', 'min' => 0],
        ];
    }












}