<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_account}}`.
 */
class m220202_051916_create_bank_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bank_account}}', [
            'id' => $this->primaryKey(),
            'account_number' => $this->string()->notNull(),
            'account_name' => $this->string()->notNull(),
            'province' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('{{%bank_account}}', 'id', $this->bigInteger() . ' NOT NULL  default(uuid_short())');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bank_account}}');
    }
}
