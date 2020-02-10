<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.06.18
 * Time: 14:32
 */

namespace frontend\controllers\cabinet;


use Yii;
use shop\entities\user\User;
use shop\forms\user\ProfileEditForm;
use shop\services\manage\cabinet\ProfileService;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller

{
    private $service;

    public function __construct($id,  $module, ProfileService $service, $config = [])
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
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }





    /**
     * @throws NotFoundHttpException
     */

    public function actionEdit()
    {
        $user = $this->findModel(Yii::$app->user->id);
        $form = new ProfileEditForm($user);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($user->id, $form);
                return $this->redirect(['/cabinet/default/index', 'id' => $user->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('edit', [
            'model' => $form,
            'user' => $user,
        ]);


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