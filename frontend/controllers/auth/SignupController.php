<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.05.18
 * Time: 14:02
 */

namespace frontend\controllers\auth;

use shop\services\auth\SignupService;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use shop\forms\auth\SignupForm;

class SignupController extends Controller
{
    public $layout = 'cabinet';

    private $service;


    public function __construct($id, $module, SignupService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;


    }


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['request'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }





    //Регистрация пользователя


    public function actionRequest()
    {
        $form = new SignupForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $this->service->signup($form);

                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();


            } catch (\DomainException $e) {

                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());

            }

        }

        return $this->render('request', [
            'model' => $form,
        ]);

    }


}