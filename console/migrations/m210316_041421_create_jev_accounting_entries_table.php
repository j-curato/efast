<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jev_accounting_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%jev_preparation}}`
 * - `{{%cash_flow}}`
 * - `{{%net_asset_equity}}`
 * - `{{%chart_of_accounts}}`
 */
class m210316_041421_create_jev_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jev_accounting_entries}}', [
            'id' => $this->primaryKey(),
            'jev_preparation_id' => $this->integer(),
            'cashflow_id' => $this->integer(),
            'net_asset_equity_id' => $this->integer(),
            'chart_of_account_id' => $this->integer(),
            'debit' => $this->decimal(15, 2),
            'credit' => $this->decimal(15, 2),
            'closing_nonclosing' => $this->string(50),
            'current_noncurrent' => $this->string(),
            'lvl' => $this->integer(),
            'object_code' => $this->string(255)


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
            'NO ACTION'
        );

        // creates index for column `cashflow_id`
        $this->createIndex(
            '{{%idx-jev_accounting_entries-cashflow_id}}',
            '{{%jev_accounting_entries}}',
            'cashflow_id'
        );

        // add foreign key for table `{{%cash_flow}}`
        $this->addForeignKey(
            '{{%fk-jev_accounting_entries-cashflow_id}}',
            '{{%jev_accounting_entries}}',
            'cashflow_id',
            '{{%cash_flow}}',
            'id',
            'NO ACTION'
        );

        // creates index for column `net_asset_equity_id`
        $this->createIndex(
            '{{%idx-jev_accounting_entries-net_asset_equity_id}}',
            '{{%jev_accounting_entries}}',
            'net_asset_equity_id'
        );

        // add foreign key for table `{{%net_asset_equity}}`
        $this->addForeignKey(
            '{{%fk-jev_accounting_entries-net_asset_equity_id}}',
            '{{%jev_accounting_entries}}',
            'net_asset_equity_id',
            '{{%net_asset_equity}}',
            'id',
            'NO ACTION'
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
            'NO ACTION'
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

        // drops foreign key for table `{{%cash_flow}}`
        $this->dropForeignKey(
            '{{%fk-jev_accounting_entries-cashflow_id}}',
            '{{%jev_accounting_entries}}'
        );

        // drops index for column `cashflow_id`
        $this->dropIndex(
            '{{%idx-jev_accounting_entries-cashflow_id}}',
            '{{%jev_accounting_entries}}'
        );

        // drops foreign key for table `{{%net_asset_equity}}`
        $this->dropForeignKey(
            '{{%fk-jev_accounting_entries-net_asset_equity_id}}',
            '{{%jev_accounting_entries}}'
        );

        // drops index for column `net_asset_equity_id`
        $this->dropIndex(
            '{{%idx-jev_accounting_entries-net_asset_equity_id}}',
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
