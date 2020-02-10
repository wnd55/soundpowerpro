<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.02.19
 * Time: 23:50
 */

namespace frontend\controllers\shop;

use Yii;
use shop\cart\Cart;
use shop\entities\shop\order\OrderUser;
use shop\entities\user\User;
use shop\event\Notificator;
use shop\forms\shop\order\OrderUserForm;
use shop\services\shop\OrderUserService;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

class CheckoutController extends Controller
{


    public $layout = 'blank';

    private $service;
    private $userService;
    private $cart;


    public function __construct($id, $module, Cart $cart, OrderUserService $userService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->cart = $cart;

        $this->userService = $userService;
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/auth/signup/request']);

        } else {
            $userId = Yii::$app->user->identity->getId();
            $user = User::findOne($userId);
            $form = new OrderUserForm($user);
            $cart = $this->cart->loadCartItems();
            $totalCount = $this->cart->totalCount();


            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {

                    $newOrder = $this->userService->checkoutUser($user, $form, $cart, $totalCount);

                    Yii::$app->session->setFlash('success', 'Ваш заказ оформлен!');

                    return $this->redirect(['order', 'id' => $newOrder->id,]);

                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }

        }

        return $this->render('index', [
            'cart' => $cart,
            'totalCount' => $totalCount,
            'model' => $form
        ]);


    }

    /**
     * @param $id
     * @return string
     */

    public function actionOrder($id)
    {

        return $this->render('order', [

            'order' => $this->findUserModel($id),

        ]);
    }


    /**
     * @param integer $id
     * @return OrderUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findUserModel($id)
    {
        if (($model = OrderUser::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


   

}
