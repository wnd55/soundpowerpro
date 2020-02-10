<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 14:06
 */

namespace frontend\controllers\auth;

use Yii;
use shop\entities\user\User;
use shop\forms\auth\LoginForm;
use shop\services\auth\AuthService;
use yii\filters\AccessControl;
use yii\web\Controller;


class AuthController extends Controller

{
    public $layout = 'cabinet';

    private $service;

    public function __construct($id, $module, AuthService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;

    }





    /**
     * @return mixed
     */

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {

            try {
                $user = $this->service->auth($form);
                Yii::$app->user->login(new User($user), $form->rememberMe ? \Yii::$app->params['user.rememberMeDuration'] : 0);
                return $this->goBack();

            } catch (\DomainException $e) {

                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', ['model' => $form]);

    }


    /**
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}