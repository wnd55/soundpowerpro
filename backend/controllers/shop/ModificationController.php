<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 30.03.18
 * Time: 13:57
 */

namespace backend\controllers\shop;

use shop\entities\shop\product\Modification;
use shop\forms\manage\shop\product\ModificationEditForm;
use Yii;
use shop\entities\shop\product\Product;
use shop\forms\manage\shop\product\ModificationCreateForm;
use shop\services\manage\shop\ProductManageService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ModificationController extends Controller
{

    private $service;

    public function __construct($id,  $module, ProductManageService $service, array $config = [])
    {

        $this->service = $service;

        parent::__construct($id, $module, $config);


    }


    public function actionCreate($product_id)
    {
    $product = $this->findModel($product_id);

    $form = new ModificationCreateForm($product_id, $product->code, $product->quantity);


        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {

                $this->service->addModification($product->id, $form);

                return $this->redirect(['shop/product/view', 'id' => $product->id, '#' => 'modifications']);

            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }




        return $this->render('create', [
            'product' => $product,
            'model' => $form,
        ]);

    }

    /**
     * @param integer $product_id
     * @param integer $id
     * @return mixed
     * @throws
     */

    public function actionUpdate($product_id, $id)
  {
      $product = $this->findModel($product_id);

      $modification = $this->findModification($id);

      $form = new ModificationEditForm($product_id,$modification);

      if ($form->load(Yii::$app->request->post()) && $form->validate()) {
          try {
              $this->service->editModification($product->id, $modification->id, $form);
              return $this->redirect(['shop/product/view', 'id' => $product->id, '#' => 'modifications']);
          } catch (\DomainException $e) {
              Yii::$app->errorHandler->logException($e);
              Yii::$app->session->setFlash('error', $e->getMessage());
          }
      }


      return $this->render('update', [
          'product' => $product,
          'model' => $form,
          'modification' => $modification,
      ]);

  }


    /**
     * @param $product_id
     * @param integer $id
     * @return mixed
     * @throws
     */

    public function actionDelete($product_id, $id)
    {

        try {
            $this->service->removeModification($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['shop/product/view', 'id' => $product_id, '#' => 'modifications']);
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
     * @return Modification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModification($id)
    {
        if (($model = Modification::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}