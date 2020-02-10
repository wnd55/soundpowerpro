<?php

use yii\db\Migration;

/**
 * Class m190217_150752_add_shop_orders_user_table
 */
class m190217_150752_add_shop_orders_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_orders_user}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'delivery_method_id' => $this->integer(),
            'delivery_method_name' => $this->string()->notNull(),
            'delivery_cost' => $this->integer()->notNull(),
            'payment_method' => $this->string(),
            'cost' => $this->integer()->notNull(),
            'note' => $this->text(),
            'current_status' => $this->integer()->notNull(),
            'cancel_reason' => $this->text(),
            'customer_name' => $this->string(),

        ], $tableOptions);

        $this->createIndex('{{%idx-shop_orders_user-user_id}}', '{{%shop_orders_user}}', 'user_id');
        $this->createIndex('{{%idx-shop_orders_user-delivery_method_id}}', '{{%shop_orders_user}}', 'delivery_method_id');

        $this->addForeignKey('{{%fk-shop_orders_user-user_id}}', '{{%shop_orders_user}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-shop_orders_user-delivery_method_id}}', '{{%shop_orders_user}}', 'delivery_method_id', '{{%shop_delivery_methods}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190217_150752_add_shop_orders_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190217_150752_add_shop_orders_user_table cannot be reverted.\n";

        return false;
    }
    */
}
