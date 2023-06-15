<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rci_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%rci}}`
 * - `{{%cash_disbursement_items}}`
 */
class m230608_054302_create_rci_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rci_items}}', [
            'id' => $this->primaryKey(),
            'fk_rci_id' => $this->bigInteger()->notNull(),
            'fk_cash_disbursement_item_id' => $this->integer()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false)->notNull(),
            'deleted_at' => $this->timestamp()->defaultValue(NULL),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_rci_id`
        $this->createIndex(
            '{{%idx-rci_items-fk_rci_id}}',
            '{{%rci_items}}',
            'fk_rci_id'
        );

        // add foreign key for table `{{%rci}}`
        $this->addForeignKey(
            '{{%fk-rci_items-fk_rci_id}}',
            '{{%rci_items}}',
            'fk_rci_id',
            '{{%rci}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_cash_disbursement_item_id`
        $this->createIndex(
            '{{%idx-rci_items-fk_cash_disbursement_item_id}}',
            '{{%rci_items}}',
            'fk_cash_disbursement_item_id'
        );

        // add foreign key for table `{{%cash_disbursement_items}}`
        $this->addForeignKey(
            '{{%fk-rci_items-fk_cash_disbursement_item_id}}',
            '{{%rci_items}}',
            'fk_cash_disbursement_item_id',
            '{{%cash_disbursement_items}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%rci}}`
        $this->dropForeignKey(
            '{{%fk-rci_items-fk_rci_id}}',
            '{{%rci_items}}'
        );

        // drops index for column `fk_rci_id`
        $this->dropIndex(
            '{{%idx-rci_items-fk_rci_id}}',
            '{{%rci_items}}'
        );

        // drops foreign key for table `{{%cash_disbursement_items}}`
        $this->dropForeignKey(
            '{{%fk-rci_items-fk_cash_disbursement_item_id}}',
            '{{%rci_items}}'
        );

        // drops index for column `fk_cash_disbursement_item_id`
        $this->dropIndex(
            '{{%idx-rci_items-fk_cash_disbursement_item_id}}',
            '{{%rci_items}}'
        );

        $this->dropTable('{{%rci_items}}');
    }
}
