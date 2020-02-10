<?php

namespace shop\helpers;

use yii\helpers\Html;

class PriceHelper
{
    public static function format($price)
    {
        return number_format($price, 0, '.', ' '.'').\Yii::$app->formatter->asHtml(' <i class="fa fa-ruble-sign" aria-hidden="true"></i>');
    }
} 