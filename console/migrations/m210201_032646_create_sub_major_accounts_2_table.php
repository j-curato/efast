<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sub_major_accounts_2}}`.
 */
class m210201_032646_create_sub_major_accounts_2_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sub_major_accounts_2}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'object_code' => $this->string(20)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sub_major_accounts_2}}');
    }
}
