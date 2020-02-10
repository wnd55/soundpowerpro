<?php

use yii\db\Migration;

/**
 * Class m190204_141134_shop_brands_table
 */
class m190204_141134_shop_brands_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_brands}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'meta_title' => $this->string()->notNull(),
            'meta_description' => $this->string()->notNull(),
            'meta_keywords' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_brands-slug}}', '{{%shop_brands}}', 'slug', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_brands}}');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190204_141134_shop_brands_table cannot be reverted.\n";

        return false;
    }
    */
}
