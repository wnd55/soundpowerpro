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

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190819_194210_add_audio_steams_table cannot be reverted.\n";

        return false;
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
