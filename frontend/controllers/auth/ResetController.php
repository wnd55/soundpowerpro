<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 16:36
 */
namespace frontend\controllers\auth;

use shop\forms\auth\ResetPasswordForm;
use Yii;
use shop\forms\auth\PasswordResetRequestForm;
use shop\services\auth\PasswordResetService;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class ResetController extends Controller
{

    public $layout = 'cabinet';

    private $service;

    public function __construct($id, $module, PasswordResetService $service, $config = [])
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
                        'actions' => ['request', 'confirm'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @return mixed
     */

    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();
        if($form->load(Yii::$app->request->post()) && $form->validate()){

            try {
                $this->service->request($form);
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

    /**
     * @param $token
     * @return mixed
     * @throws BadRequestHttpException
     *
     */


    public function actionConfirm($token)
    {
        try{
            $this->service->validateToken($token);

        }catch (\DomainException $e){

            throw new BadRequestHttpException($e->getMessage());
        }

        $form = new ResetPasswordForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->reset($token, $form);
                Yii::$app->session->setFlash('success', 'New password saved.');
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->goHome();
        }


        return $this->render('confirm', [
            'model' => $form,
        ]);
    }

}