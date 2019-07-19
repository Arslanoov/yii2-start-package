<?php

use yii\db\Migration;

/**
 * Class m190719_143334_create_user_roles
 */
class m190719_143334_create_user_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%auth_items}}', ['type', 'name', 'description'], [
            [1, 'user', 'User'],
            [1, 'manager', 'Manager'],
            [1, 'content-manager', 'Content Manager'],
            [1, 'admin', 'Admin'],
        ]);

        $this->batchInsert('{{%auth_item_children}}', ['parent', 'child'], [
            ['manager', 'user'],
            ['content-manager', 'user'],
            ['admin', 'manager'],
            ['admin', 'content-manager'],
        ]);

        $this->execute('INSERT INTO {{%auth_assignments}} (item_name, user_id) SELECT \'user\', u.id FROM {{%users}} u ORDER BY u.id');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->delete('{{%auth_items}}', ['name' => ['user', 'manager', 'content-manager', 'admin']]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190719_143334_create_user_roles cannot be reverted.\n";

        return false;
    }
    */
}
