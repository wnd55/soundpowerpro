<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.02.19
 * Time: 21:54
 */

namespace backend\controllers\shop;

use backend\forms\shop\OrderUserSearch;
use shop\entities\shop\order\OrderUser;
use shop\event\Notificator;
use shop\forms\manage\shop\order\OrderEditUserForm;
use shop\services\manage\shop\OrderUserManageService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class OrderUserController extends Controller
{

    private $service;

    public function __construct($id, $module, OrderUserManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;

    }


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'export' => ['POST'],
                    'delete' => ['POST'],
                    'add-wav' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderUserSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */

    public function actionView($id)
    {
        return $this->render('view', [
            'order' => $this->findModel($id),
        ]);
    }


    /**
     * @param integer $id
     * @return OrderUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderUser::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */

    public function actionUpdate($id)
    {
        $order = $this->findModel($id);

        $form = new OrderEditUserForm($order);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {

                $this->service->edit($order->id, $form);
                return $this->redirect(['view', 'id' => $order->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'order' => $order,
        ]);
    }


    /**
     * @return \yii\web\Response
     */

    public function actionAddWav()
    {

        if (Yii::$app->request->isPost) {

            try {
                $this->service->addOrderWav();

            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

        }

        return $this->redirect(Yii::$app->request->referrer);

    }


}