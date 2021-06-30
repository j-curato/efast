<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advances_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%advances}}`
 * - `{{%cash_disbursement}}`
 * - `{{%sub_accounts1}}`
 */
class m210505_033130_create_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%advances_entries}}', [
            'id' => $this->primaryKey(),
            'advances_id' => $this->integer(),
            'cash_disbursement_id' => $this->integer(),
            'sub_account1_id' => $this->integer(),
            'amount'=>$this->decimal(10,2),
            'object_code' => $this->string()->defaultValue(null),
            'fund_source' => $this->text(),
            'book_id' => $this->integer(),
        ]);

        // creates index for column `advances_id`
        $this->createIndex(
            '{{%idx-advances_entries-advances_id}}',
            '{{%advances_entries}}',
            'advances_id'
        );

        // add foreign key for table `{{%advances}}`
        $this->addForeignKey(
            '{{%fk-advances_entries-advances_id}}',
            '{{%advances_entries}}',
            'advances_id',
            '{{%advances}}',
            'id',
            'CASCADE'
        );

        // creates index for column `cash_disbursement_id`
        $this->createIndex(
            '{{%idx-advances_entries-cash_disbursement_id}}',
            '{{%advances_entries}}',
            'cash_disbursement_id'
        );

        // add foreign key for table `{{%cash_disbursement}}`
        $this->addForeignKey(
            '{{%fk-advances_entries-cash_disbursement_id}}',
            '{{%advances_entries}}',
            'cash_disbursement_id',
            '{{%cash_disbursement}}',
            'id',
            'CASCADE'
        );

        // creates index for column `sub_account1_id`
        $this->createIndex(
            '{{%idx-advances_entries-sub_account1_id}}',
            '{{%advances_entries}}',
            'sub_account1_id'
        );

        // add foreign key for table `{{%sub_accounts1}}`
        $this->addForeignKey(
            '{{%fk-advances_entries-sub_account1_id}}',
            '{{%advances_entries}}',
            'sub_account1_id',
            '{{%sub_accounts1}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%advances}}`
        $this->dropForeignKey(
            '{{%fk-advances_entries-advances_id}}',
            '{{%advances_entries}}'
        );

        // drops index for column `advances_id`
        $this->dropIndex(
            '{{%idx-advances_entries-advances_id}}',
            '{{%advances_entries}}'
        );

        // drops foreign key for table `{{%cash_disbursement}}`
        $this->dropForeignKey(
            '{{%fk-advances_entries-cash_disbursement_id}}',
            '{{%advances_entries}}'
        );

        // drops index for column `cash_disbursement_id`
        $this->dropIndex(
            '{{%idx-advances_entries-cash_disbursement_id}}',
            '{{%advances_entries}}'
        );

        // drops foreign key for table `{{%sub_accounts1}}`
        $this->dropForeignKey(
            '{{%fk-advances_entries-sub_account1_id}}',
            '{{%advances_entries}}'
        );

        // drops index for column `sub_account1_id`
        $this->dropIndex(
            '{{%idx-advances_entries-sub_account1_id}}',
            '{{%advances_entries}}'
        );

        $this->dropTable('{{%advances_entries}}');
    }
}
