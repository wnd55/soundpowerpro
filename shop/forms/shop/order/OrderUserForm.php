<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.02.19
 * Time: 15:37
 */

namespace shop\forms\shop\order;





use shop\entities\shop\Delivery;
use shop\entities\user\User;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class OrderUserForm extends ActiveRecord
{
    public $user_id;
    public $delivery;
    public $index;
    public $address;
    public $note;

    public $name;

    protected $_user;

public function __construct(User $user, array $config = [])
{

   parent::__construct($config);
}


    public function rules()
    {
        return [
            [['note'], 'string'],
            [['delivery', 'user_id'], 'integer'],
            [['delivery',], 'required'],
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
            'created_at' => 'Created At',
            'delivery' => 'Варианты доставки',
            'delivery_method_name' => 'Delivery Method Name',
            'delivery_cost' => 'Delivery Cost',
            'payment_method' => 'Payment Method',
            'cost' => 'Cost',
            'note' => 'Комментарии',
            'current_status' => 'Current Status',
            'cancel_reason' => 'Cancel Reason',
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