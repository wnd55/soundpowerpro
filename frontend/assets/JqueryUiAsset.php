<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.03.19
 * Time: 20:54
 */



namespace frontend\assets;


use yii\web\AssetBundle;

class JqueryUiAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';
    public $js = [
        'jquery-ui.js',
    ];
}
