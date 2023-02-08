<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%process_ors_txn_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%process_ors}}`
 * - `{{%transaction_items}}`
 */
class m230202_014343_create_process_ors_txn_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%process_ors_txn_items}}', [
            'id' => $this->primaryKey(),
            'fk_process_ors_id' => $this->integer(),
            'fk_transaction_item_id' => $this->integer(),
            'amount' => $this->decimal(10, 2),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_process_ors_id`
        $this->createIndex(
            '{{%idx-fk_process_ors_id}}',
            '{{%process_ors_txn_items}}',
            'fk_process_ors_id'
        );

        // add foreign key for table `{{%process_ors}}`
        $this->addForeignKey(
            '{{%fk-fk_process_ors_id}}',
            '{{%process_ors_txn_items}}',
            'fk_process_ors_id',
            '{{%process_ors}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_transaction_item_id`
        $this->createIndex(
            '{{%idx-fk_transaction_item_id}}',
            '{{%process_ors_txn_items}}',
            'fk_transaction_item_id'
        );

        // add foreign key for table `{{%transaction_items}}`
        $this->addForeignKey(
            '{{%fk-fk_transaction_item_id}}',
            '{{%process_ors_txn_items}}',
            'fk_transaction_item_id',
            '{{%transaction_items}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%process_ors}}`
        $this->dropForeignKey(
            '{{%fk-fk_process_ors_id}}',
            '{{%process_ors_txn_items}}'
        );

        // drops index for column `fk_process_ors_id`
        $this->dropIndex(
            '{{%idx-fk_process_ors_id}}',
            '{{%process_ors_txn_items}}'
        );

        // drops foreign key for table `{{%transaction_items}}`
        $this->dropForeignKey(
            '{{%fk-fk_transaction_item_id}}',
            '{{%process_ors_txn_items}}'
        );

        // drops index for column `fk_transaction_item_id`
        $this->dropIndex(
            '{{%idx-fk_transaction_item_id}}',
            '{{%process_ors_txn_items}}'
        );

        $this->dropTable('{{%process_ors_txn_items}}');
    }
}
