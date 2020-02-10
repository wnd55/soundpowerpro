<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.02.19
 * Time: 20:39
 */

namespace shop\forms\manage\shop\order;

use shop\entities\shop\Delivery;
use shop\entities\shop\order\OrderUser;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class OrderEditUserForm extends Model
{

    public $delivery;

    public $note;

    public $name;
    public $status;


    public function __construct(OrderUser $orderUser, array $config = [])
    {
        $this->delivery = $orderUser->delivery_method_id;
        $this->note = $orderUser->note;
        $this->name = $orderUser->customer_name;
        $this->status = $orderUser->current_status;



        parent::__construct($config);
    }



    public function rules()
    {
        return [
            [['note'], 'string'],
            [['delivery', 'status'], 'integer'],

            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'delivery' => 'Варианты доставки',
            'delivery_method_name' => 'Доставка',
            'delivery_cost' => 'Стоимость доставки',
            'payment_method' => 'Метод оплаты',
            'cost' => 'Полная стоимость заказа',
            'note' => 'Комментарии',
            'current_status' => 'Статус заказа',
            'cancel_reason' => 'Причина отмены',
            'name' => 'ФИО',


        ];
    }


    /**
     * @return array
     */

    public function deliveryMethodsList()
    {
        $delivery = Delivery::find()->orderBy('sort')->all();

        return ArrayHelper::map($delivery, 'id', function (Delivery $delivery) {
            return $delivery->name . ' (' . $delivery->cost .')';
        });
    }




}