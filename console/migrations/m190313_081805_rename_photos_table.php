<?php

use yii\db\Migration;

/**
 * Class m190313_081805_rename_photos_table
 */
class m190313_081805_rename_photos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%shop_photos}}', '{{%shop_audio}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('{{%shop_photos}}', '{{%shop_audio}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190313_081805_rename_photos_table cannot be reverted.\n";

        return false;
    }
    */
}
