<?php

use yii\db\Migration;

/**
 * Class m190204_144956_shop_photos_table
 */
class m190204_144956_shop_photos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_photos}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_photos-product_id}}', '{{%shop_photos}}', 'product_id');

        $this->addForeignKey('{{%fk-shop_photos-product_id}}', '{{%shop_photos}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_photos}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190204_144956_shop_photos_table cannot be reverted.\n";

        return false;
    }
    */
}
