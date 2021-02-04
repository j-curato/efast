<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_accounts}}`.
 */
class m210201_094316_create_bank_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bank_accounts}}', [
            'id' => $this->primaryKey(),
            'fund_cluster' => $this->string(255)->notNull(),
            'account_number' => $this->string(50)->notNull(),
            'account_name' => $this->string(255)->notNull(),
            'nature' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bank_accounts}}');
    }
}
