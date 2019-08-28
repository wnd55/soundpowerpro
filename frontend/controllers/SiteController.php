<?php
namespace frontend\controllers;

use yii\web\Controller;


/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = 'chat';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


//    public function behaviors()
//    {
//        return [
//
//            [
//                'class' => 'yii\filters\PageCache',
//                'only' => ['index'],
//                'duration' => 60,
//
//            ],
//
//            [
//                'class' => 'yii\filters\HttpCache',
//                'cacheControlHeader' => 'public, max-age=3600',
//            ],
//
//
//
//        ];
//
//    }
//





    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()

    {

        return $this->render('index');
    }






}
