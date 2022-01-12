<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_purchase_request_item}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%pr_purchase_request}}`
 */
class m220112_020202_create_pr_purchase_request_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_purchase_request_item}}', [
            'id' => $this->primaryKey(),
            'pr_purchase_request_id' => $this->integer(),
            'pr_stock_id' => $this->integer(),
            'quantity' => $this->integer(),
            'unit_cost' => $this->decimal(10, 2),
            'specification' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `pr_purchase_request_id`
        $this->createIndex(
            '{{%idx-pr_purchase_request_item-pr_purchase_request_id}}',
            '{{%pr_purchase_request_item}}',
            'pr_purchase_request_id'
        );

        // add foreign key for table `{{%pr_purchase_request}}`
        $this->addForeignKey(
            '{{%fk-pr_purchase_request_item-pr_purchase_request_id}}',
            '{{%pr_purchase_request_item}}',
            'pr_purchase_request_id',
            '{{%pr_purchase_request}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%pr_purchase_request}}`
        $this->dropForeignKey(
            '{{%fk-pr_purchase_request_item-pr_purchase_request_id}}',
            '{{%pr_purchase_request_item}}'
        );

        // drops index for column `pr_purchase_request_id`
        $this->dropIndex(
            '{{%idx-pr_purchase_request_item-pr_purchase_request_id}}',
            '{{%pr_purchase_request_item}}'
        );

        $this->dropTable('{{%pr_purchase_request_item}}');
    }
}
