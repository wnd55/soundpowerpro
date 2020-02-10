<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.02.19
 * Time: 13:26
 */

namespace backend\assets;

use yii\web\AssetBundle;

class WavesurferAsset extends AssetBundle
{


    public $js = [

        ['js/wavesurfer.js', 'position' => \yii\web\View::POS_HEAD]

    ];


}