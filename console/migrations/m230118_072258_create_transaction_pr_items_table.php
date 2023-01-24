<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction_pr_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%transaction}}`
 * - `{{%pr_purchase_request}}`
 */
class m230118_072258_create_transaction_pr_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction_pr_items}}', [
            'id' => $this->primaryKey(),
            'fk_transaction_id' => $this->bigInteger(),
            'fk_pr_purchase_request_id' => $this->bigInteger(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_transaction_id`
        $this->createIndex(
            '{{%idx-transaction_pr_items-fk_transaction_id}}',
            '{{%transaction_pr_items}}',
            'fk_transaction_id'
        );

        // add foreign key for table `{{%transaction}}`
        $this->addForeignKey(
            '{{%fk-transaction_pr_items-fk_transaction_id}}',
            '{{%transaction_pr_items}}',
            'fk_transaction_id',
            '{{%transaction}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_pr_purchase_request_id`
        $this->createIndex(
            '{{%idx-transaction_pr_items-fk_pr_purchase_request_id}}',
            '{{%transaction_pr_items}}',
            'fk_pr_purchase_request_id'
        );

        // add foreign key for table `{{%pr_purchase_request}}`
        $this->addForeignKey(
            '{{%fk-transaction_pr_items-fk_pr_purchase_request_id}}',
            '{{%transaction_pr_items}}',
            'fk_pr_purchase_request_id',
            '{{%pr_purchase_request}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%transaction}}`
        $this->dropForeignKey(
            '{{%fk-transaction_pr_items-fk_transaction_id}}',
            '{{%transaction_pr_items}}'
        );

        // drops index for column `fk_transaction_id`
        $this->dropIndex(
            '{{%idx-transaction_pr_items-fk_transaction_id}}',
            '{{%transaction_pr_items}}'
        );

        // drops foreign key for table `{{%pr_purchase_request}}`
        $this->dropForeignKey(
            '{{%fk-transaction_pr_items-fk_pr_purchase_request_id}}',
            '{{%transaction_pr_items}}'
        );

        // drops index for column `fk_pr_purchase_request_id`
        $this->dropIndex(
            '{{%idx-transaction_pr_items-fk_pr_purchase_request_id}}',
            '{{%transaction_pr_items}}'
        );

        $this->dropTable('{{%transaction_pr_items}}');
    }
}
