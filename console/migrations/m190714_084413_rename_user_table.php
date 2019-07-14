<?php

use yii\db\Migration;

/**
 * Class m190714_084413_rename_user_table
 */
class m190714_084413_rename_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%user}}', '{{%users}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('{{%users}}', '{{%user}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190714_084413_rename_user_table cannot be reverted.\n";

        return false;
    }
    */
}
