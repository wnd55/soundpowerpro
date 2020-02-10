<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.02.19
 * Time: 20:34
 */

namespace shop\services\manage\shop;


use shop\forms\manage\shop\order\OrderEditUserForm;
use shop\repositories\shop\DeliveryRepository;
use shop\repositories\shop\OrderUserRepository;
use yii\web\UploadedFile;

class OrderUserManageService
{

    private $orders;
    private $deliveryMethods;

    public function __construct(OrderUserRepository $orders, DeliveryRepository $deliveryMethods)
    {

        $this->orders = $orders;
        $this->deliveryMethods = $deliveryMethods;


    }


    /**
     * @param $id
     * @param OrderEditUserForm $form
     * @throws \yii\web\NotFoundHttpException
     */

    public function edit($id, OrderEditUserForm $form)
    {
        $order = $this->orders->get($id);
        $order->edit(
            $form->delivery,
            $form->note,
            $form->name,
            $form->status
        );



        $this->orders->save($order);


    }

    /**
     * @param $id
     */

    public function remove($id)
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }


    /**
     *
     */
    public function addOrderWav()
    {

        $model = UploadedFile::getInstanceByName('wav');


        if ($model->getExtension() != 'wav') {

            throw new \DomainException('Для загрузки необходим wav файл.');
        }


        $model->saveAs('uploads/wav/' . $model->getBaseName() . '.' . $model->getExtension());

    }





}