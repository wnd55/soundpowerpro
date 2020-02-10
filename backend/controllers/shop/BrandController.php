<?php
/**
 * Created by PhpStorm.
 * User: MaximIV
 * Date: 18.03.2018
 * Time: 15:57
 */

namespace backend\controllers\shop;

use backend\forms\shop\BrandSearchForm;
use shop\entities\shop\Brand;
use shop\services\manage\shop\BrandManageService;
use Yii;
use shop\forms\manage\shop\BrandForm;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BrandController extends Controller
{

    private $service;

    public function __construct($id, $module, BrandManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */

    public function actionIndex()
    {

        $search = new BrandSearchForm();
        $dataProvider = $search->search(Yii::$app->request->queryParams);

        return $this->render('index', ['search' => $search, 'dataProvider' => $dataProvider]);


    }


    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new BrandForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $brand = $this->service->create($form);
                return $this->redirect(['view', 'id' => $brand->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws
     */

    public function actionView($id)
    {
        return $this->render('view', [
            'brand' => $this->findBrand($id),

        ]);


    }

    protected function findBrand($id)
    {
        if (($brand = Brand::findOne($id)) !== null) {

            return $brand;
        }
        throw new \DomainException('The requested page does not exist');
    }

    /**
     * @param
     * @return mixed
     * @throws
     */


    public function actionUpdate($id)
    {
        $brand = $this->findBrand($id);

        $form = new BrandForm($brand);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {

                $this->service->edit($brand->id, $form);
                return $this->redirect(['view', 'id' => $brand->id]);


            } catch (\DomainException $e) {

                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }


        return $this->render('update', [

            'model' => $form,
            'brand' => $brand

        ]);


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


}