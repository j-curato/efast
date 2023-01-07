<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction_items}}`.
 */
class m221215_081234_create_transaction_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction_items}}', [
            'id' => $this->primaryKey(),
            'fk_transaction_id' => $this->bigInteger()->notNull(),
            'fk_record_allotment_entries_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(0)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%transaction_items}}');
    }
}
