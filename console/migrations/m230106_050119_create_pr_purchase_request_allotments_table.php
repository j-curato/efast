<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_purchase_request_allotments}}`.
 */
class m230106_050119_create_pr_purchase_request_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_purchase_request_allotments}}', [
            'id' => $this->primaryKey(),
            'fk_purchase_request_id' => $this->bigInteger()->notNull(),
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
        $this->dropTable('{{%pr_purchase_request_allotments}}');
    }
}
