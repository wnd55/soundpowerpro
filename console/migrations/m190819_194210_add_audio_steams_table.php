<?php

use yii\db\Migration;

/**
 * Class m190819_194210_add_audio_steams_table
 */
class m190819_194210_add_audio_steams_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_audio_stems}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'status' =>$this->smallInteger()->notNull(),
            'sort' => $this->integer()->notNull(),

        ], $tableOptions);

        $this->createIndex('{{%idx-shop_audio_stems-product_id}}', '{{%shop_audio_stems}}', 'product_id');
        $this->addForeignKey('{{%fk-shop_audio_stems-product_id}}', '{{%shop_audio_stems}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_audio_stems}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190819_194210_add_audio_steams_table cannot be reverted.\n";

        return false;
    }
    */
}
