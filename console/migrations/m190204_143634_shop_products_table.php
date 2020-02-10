<?php

use yii\db\Migration;

/**
 * Class m190204_143634_shop_products_table
 */
class m190204_143634_shop_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_products}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'code' => $this->string(),
            'name' => $this->string()->notNull(),
            'description'=>$this->text(),
            'price_old' => $this->integer(),
            'price_new' => $this->integer(),
            'weight' => $this->integer(),
            'quantity' => $this->integer(),
            'recommended' => $this->boolean(),
            'popular'=> $this->boolean(),
            'rating' => $this->decimal(3, 2),
            'status' =>$this->smallInteger()->notNull(),
            'meta_title' => $this->string()->notNull(),
            'meta_description' => $this->string()->notNull(),
            'meta_keywords' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_products-code}}', '{{%shop_products}}', 'code', true);

        $this->createIndex('{{%idx-shop_products-category_id}}', '{{%shop_products}}', 'category_id');
        $this->createIndex('{{%idx-shop_products-brand_id}}', '{{%shop_products}}', 'brand_id');

        $this->addForeignKey('{{%fk-shop_products-category_id}}', '{{%shop_products}}', 'category_id', '{{%shop_categories}}', 'id');
        $this->addForeignKey('{{%fk-shop_products-brand_id}}', '{{%shop_products}}', 'brand_id', '{{%shop_brands}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_products}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190204_143634_shop_products_table cannot be reverted.\n";

        return false;
    }
    */
}
