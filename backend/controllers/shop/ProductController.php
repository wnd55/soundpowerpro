<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.03.18
 * Time: 22:33
 */

namespace backend\controllers\shop;

use backend\forms\shop\ProductSearch;
use shop\entities\shop\product\Modification;
use shop\entities\shop\product\Product;
use shop\forms\manage\shop\product\AudioFilesForm;
use shop\forms\manage\shop\product\PriceForm;
use shop\forms\manage\shop\product\ProductEditForm;
use shop\forms\manage\shop\product\QuantityForm;
use shop\services\manage\shop\ProductManageService;
use Yii;
use shop\forms\manage\shop\product\ProductCreateForm;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ProductController extends Controller
{


    private $service;

    public function __construct($id, $module, ProductManageService $service, array $config = [])
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
                    'activate' => ['POST'],
                    'draft' => ['POST'],
                    'delete-audio' => ['POST'],
                    'make-main-photo' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new ProductCreateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $product = $this->service->create($form);
                return $this->redirect(['view', 'id' => $product->id]);
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

        $product = $this->findModel($id);

        $photosForm = new AudioFilesForm();

        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($product->id, $photosForm);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'product' => $product,
            'photosForm' => $photosForm,
        ]);
    }


    /**
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
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
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }


    /**
     * @param integer $id
     * @return mixed
     * @throws
     */

    public function actionUpdate($id)
    {
        $product = $this->findModel($id);

        $form = new ProductEditForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {

                $this->service->edit($product->id, $form);

                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'product' => $product,
        ]);
    }


    /**
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        try {
            $this->service->activate($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDraft($id)
    {
        try {
            $this->service->draft($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $id]);
    }


    /**
     * @param integer $id
     * @return mixed
     * @throws
     */


    public function actionPrice($id)
    {

        $product = $this->findModel($id);

        $form = new PriceForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->changePrice($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('price', [
            'model' => $form,
            'product' => $product,
        ]);


    }

    /**
     * @param integer $id
     * @return mixed
     * @throws
     */


    public function actionQuantity($id)
    {
        $product = $this->findModel($id);

        $form = new QuantityForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->changeQuantity($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('quantity', [
            'model' => $form,
            'product' => $product,
        ]);
    }



    /**
     * @param integer $productId
     * @param $photoId
     * @return mixed
     * @throws
     */

    public function actionDeleteAudio($productId, $photoId)
    {
        try {
            $this->service->removePhoto($productId, $photoId);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $productId, '#' => 'photos']);
    }


    /**
     * @param integer $productId
     * @param $photoId
     * @return mixed
     * @throws
     */


    public function actionMakeMainPhoto($productId, $photoId)
    {

        try {

            $this->service->makeMainPhoto($productId, $photoId);


        } catch (\DomainException $e) {

            Yii::$app->session->setFlash('error', $e->getMessage());
        }


        return $this->redirect(['view', 'id' => $productId, '#' => 'photos']);
    }

}