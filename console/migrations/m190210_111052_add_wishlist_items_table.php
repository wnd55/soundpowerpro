<?php

use yii\db\Migration;

/**
 * Class m190210_111052_add_wishlist_items_table
 */
class m190210_111052_add_wishlist_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%user_wishlist_items}}', [
            'user_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-user_wishlist_items}}', '{{%user_wishlist_items}}', ['user_id', 'product_id']);

        $this->createIndex('{{%idx-user_wishlist_items-user_id}}', '{{%user_wishlist_items}}', 'user_id');
        $this->createIndex('{{%idx-user_wishlist_items-product_id}}', '{{%user_wishlist_items}}', 'product_id');

        $this->addForeignKey('{{%fk-user_wishlist_items-user_id}}', '{{%user_wishlist_items}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-user_wishlist_items-product_id}}', '{{%user_wishlist_items}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_wishlist_items}}');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190210_111052_add_wishlist_items_table cannot be reverted.\n";

        return false;
    }
    */
}
