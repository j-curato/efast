<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dv_transaction_type}}`.
 */
class m230217_014930_create_dv_transaction_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dv_transaction_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'create_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dv_transaction_type}}');
    }
}
