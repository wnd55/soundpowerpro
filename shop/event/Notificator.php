<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.02.19
 * Time: 17:40
 */

namespace shop\event;


use shop\entities\shop\order\OrderGuest;
use shop\entities\shop\order\OrderUser;
use shop\entities\shop\order\OrderUserItem;
use shop\entities\user\User;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\mail\MailerInterface;
use yii\swiftmailer\Mailer;

class Notificator implements BootstrapInterface
{
    public $mailer;

    public function __construct(MailerInterface $mailer)
    {

        $this->mailer = $mailer;

    }



    public function bootstrap($app)
    {



        Event::on(OrderUser::class,
            ActiveRecord::EVENT_AFTER_INSERT,
            [$this, 'orderAdminNotificator']
        );





    }


    public function orderAdminNotificator(Event $event)
    {



       $sent = $this->mailer->compose(
            ['html' => 'order/order/confirmAdminOrderNotificator-html']
             )
            ->setTo(\Yii::$app->params['adminEmail'])
            ->setFrom(\Yii::$app->params['adminEmail'])
            ->setSubject('Новый заказ на сайте')
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }


    }


}
