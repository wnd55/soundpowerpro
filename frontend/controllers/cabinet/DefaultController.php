<?php

namespace frontend\controllers\cabinet;

use shop\entities\user\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;



class DefaultController extends Controller
{
    public $layout = 'cabinet';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $user = $this->findModel(\Yii::$app->user->id);

        return $this->render('index',['model' => $user]);
    }





    /**
     * @param $id
     * @return null|static
     * @return User the loaded model
     * @throws NotFoundHttpException
     */


    protected function findModel($id)
    {

        if(($model = User::findOne($id)) !== null){

            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}