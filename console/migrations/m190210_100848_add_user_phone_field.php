<?php

use yii\db\Migration;

/**
 * Class m190210_100848_add_user_phone_field
 */
class m190210_100848_add_user_phone_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'phone', $this->string()->notNull());

        $this->createIndex('{{%idx-users-phone}}', '{{%users}}', 'phone', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'phone');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190210_100848_add_user_phone_field cannot be reverted.\n";

        return false;
    }
    */
}
