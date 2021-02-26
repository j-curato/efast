<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jev_accounting_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%jev_preparation}}`
 * - `{{%chart_of_accounts}}`
 */
class m210202_032341_create_jev_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jev_accounting_entries}}', [
            'id' => $this->primaryKey(),
            'jev_preparation_id' => $this->integer()->notNull(),
            'chart_of_account_id' => $this->integer()->notNull(),
            'debit' => $this->double(),
            'credit' => $this->double(),
        ]);

        // creates index for column `jev_preparation_id`
        $this->createIndex(
            '{{%idx-jev_accounting_entries-jev_preparation_id}}',
            '{{%jev_accounting_entries}}',
            'jev_preparation_id'
        );

        // add foreign key for table `{{%jev_preparation}}`
        $this->addForeignKey(
            '{{%fk-jev_accounting_entries-jev_preparation_id}}',
            '{{%jev_accounting_entries}}',
            'jev_preparation_id',
            '{{%jev_preparation}}',
            'id',
            'CASCADE'
        );

        // creates index for column `chart_of_account_id`
        $this->createIndex(
            '{{%idx-jev_accounting_entries-chart_of_account_id}}',
            '{{%jev_accounting_entries}}',
            'chart_of_account_id'
        );

        // add foreign key for table `{{%chart_of_accounts}}`
        $this->addForeignKey(
            '{{%fk-jev_accounting_entries-chart_of_account_id}}',
            '{{%jev_accounting_entries}}',
            'chart_of_account_id',
            '{{%chart_of_accounts}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%jev_preparation}}`
        $this->dropForeignKey(
            '{{%fk-jev_accounting_entries-jev_preparation_id}}',
            '{{%jev_accounting_entries}}'
        );

        // drops index for column `jev_preparation_id`
        $this->dropIndex(
            '{{%idx-jev_accounting_entries-jev_preparation_id}}',
            '{{%jev_accounting_entries}}'
        );

        // drops foreign key for table `{{%chart_of_accounts}}`
        $this->dropForeignKey(
            '{{%fk-jev_accounting_entries-chart_of_account_id}}',
            '{{%jev_accounting_entries}}'
        );

        // drops index for column `chart_of_account_id`
        $this->dropIndex(
            '{{%idx-jev_accounting_entries-chart_of_account_id}}',
            '{{%jev_accounting_entries}}'
        );

        $this->dropTable('{{%jev_accounting_entries}}');
    }
}
