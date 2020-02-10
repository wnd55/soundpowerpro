<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.02.19
 * Time: 16:16
 */

namespace shop\services\shop;


use Yii;

use shop\entities\shop\order\OrderUser;

use shop\entities\shop\order\OrderUserItem;
use shop\forms\shop\order\OrderUserForm;
use shop\repositories\shop\DeliveryRepository;
use shop\repositories\shop\OrderUserRepository;
use shop\repositories\shop\ProductRepository;
use shop\services\TransactionManager;

use yii\base\Event;
use yii\db\ActiveRecord;
use yii\mail\MailerInterface;

class OrderUserService
{


    private $cart;
    private $orders;
    private $products;
    private $deliveryMethods;
    private $transaction;
    private $mailer;
    private $event;
    public $newOrder;






    public function __construct(
        CartService $cart, OrderUserRepository $orders, ProductRepository $products, DeliveryRepository $deliveryMethods,

        TransactionManager $transaction, MailerInterface $mailer, Event $event)
    {
        $this->cart = $cart;
        $this->orders = $orders;
        $this->products = $products;
        $this->deliveryMethods = $deliveryMethods;
        $this->transaction = $transaction;
        $this->mailer = $mailer;
        $this->event = $event;


    }

    /**
     * @param $user
     * @param OrderUserForm $form
     * @param $cart
     * @param $totalCount
     * @return OrderUser
     */

    public function checkoutUser($user, OrderUserForm $form, $cart, $totalCount)
    {
        $order = OrderUser::create($user, $form->delivery, $form->note,  $form->name);

        $delivery = $this->deliveryMethods->get($form->delivery);

        $order->delivery_method_name = $delivery->name;
        $order->delivery_cost = $delivery->cost;
        $order->cost = $totalCount + $delivery->cost;


        $this->newOrder = $this->orders->save($order);





        foreach ($cart as $i => $item) {

            $productId = (int)$item['product']->id;
            $product = $this->products->get($productId);

            $orderItem = OrderUserItem::createUser(
                $this->newOrder,
                $product,
                $item['product']->price_new

            );

            $orderItem->save();

        }
        $this->cart->clear();

        $sent = $this->mailer->compose(

            [
                'html' => 'order/order/confirmOrderUserNotificator-html',

            ],
            [
                'order' => $this->newOrder,
                'logo' => \Yii::getAlias('@common/mail/images/logo.png'),
                'ruble' => \Yii::getAlias('@common/mail/images/ruble.png')

            ]
        )
            ->setTo($user->email)
            ->setFrom(\Yii::$app->params['adminEmail'])
            ->setSubject('Заказ на сайте soundpowerpro.ru')
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }




        return $this->newOrder;
    }

    /**
     * @return mixed
     */

   public function getNewOrderUser()
    {

        return $this->newOrder;


    }


}