<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_purchase_request}}`.
 */
class m220107_050931_create_pr_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_purchase_request}}', [
            'id' => $this->primaryKey(),
            'pr_number' => $this->string()->unique()->notNull(),
            'date' => $this->date(),
            'book_id' => $this->integer(),
            'pr_project_procurement_id' => $this->bigInteger(),
            'purpose' => $this->text(),
            'requested_by_id' => $this->bigInteger(),
            'approved_by_id' => $this->bigInteger(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('{{%pr_purchase_request}}', 'id', $this->bigInteger() . ' NOT NULL default(uuid_short()) ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_purchase_request}}');
    }
}
