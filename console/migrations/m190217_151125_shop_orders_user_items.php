<?php

use yii\db\Migration;

/**
 * Class m190217_151125_shop_orders_user_items
 */
class m190217_151125_shop_orders_user_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_orders_user_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer(),
            'product_name' => $this->string()->notNull(),
            'product_code' => $this->string()->notNull(),
            'price' => $this->integer()->notNull(),

        ], $tableOptions);

        $this->createIndex('{{%idx-shop_orders_user_items-order_id}}', '{{%shop_orders_user_items}}', 'order_id');
        $this->createIndex('{{%idx-shop_orders_user_items-product_id}}', '{{%shop_orders_user_items}}', 'product_id');


        $this->addForeignKey('{{%fk-shop_orders_user_items-order_id}}', '{{%shop_orders_user_items}}', 'order_id', '{{%shop_orders_user}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-shop_orders_user_items-product_id}}', '{{%shop_orders_user_items}}', 'product_id', '{{%shop_products}}', 'id', 'SET NULL');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190217_151125_shop_orders_user_items cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190217_151125_shop_orders_user_items cannot be reverted.\n";

        return false;
    }
    */
}
