<?php

use yii\db\Migration;

/**
 * Handles dropping phone from table `{{%users}}`.
 */
class m190307_174739_drop_phone_column_from_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%users}}', 'phone');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%users}}', 'phone', $this->string());
    }
}
