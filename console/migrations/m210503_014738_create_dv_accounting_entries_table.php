<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dv_accounting_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%dv_aucs}}`
 * - `{{%cash_flow}}`
 * - `{{%net_asset_equity}}`
 * - `{{%chart_of_accounts}}`
 */
class m210503_014738_create_dv_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dv_accounting_entries}}', [
            'id' => $this->primaryKey(),
            'dv_aucs_id' => $this->integer(),
            'cashflow_id' => $this->integer(),
            'net_asset_equity_id' => $this->integer(),
            'chart_of_account_id' => $this->integer(),
            'debit' => $this->decimal(10,2),
            'credit' => $this->decimal(10,2),
            'closing_nonclosing'=>$this->string(50),
            'current_noncurrent'=>$this->string(),
            'lvl'=>$this->integer(),
            'object_code'=>$this->string(255)
        ]);

        // creates index for column `dv_aucs_id`
        $this->createIndex(
            '{{%idx-dv_accounting_entries-dv_aucs_id}}',
            '{{%dv_accounting_entries}}',
            'dv_aucs_id'
        );

        // add foreign key for table `{{%dv_aucs}}`
        $this->addForeignKey(
            '{{%fk-dv_accounting_entries-dv_aucs_id}}',
            '{{%dv_accounting_entries}}',
            'dv_aucs_id',
            '{{%dv_aucs}}',
            'id',
            'CASCADE'
        );

        // creates index for column `cashflow_id`
        $this->createIndex(
            '{{%idx-dv_accounting_entries-cashflow_id}}',
            '{{%dv_accounting_entries}}',
            'cashflow_id'
        );

        // add foreign key for table `{{%cash_flow}}`
        $this->addForeignKey(
            '{{%fk-dv_accounting_entries-cashflow_id}}',
            '{{%dv_accounting_entries}}',
            'cashflow_id',
            '{{%cash_flow}}',
            'id',
            'CASCADE'
        );

        // creates index for column `net_asset_equity_id`
        $this->createIndex(
            '{{%idx-dv_accounting_entries-net_asset_equity_id}}',
            '{{%dv_accounting_entries}}',
            'net_asset_equity_id'
        );

        // add foreign key for table `{{%net_asset_equity}}`
        $this->addForeignKey(
            '{{%fk-dv_accounting_entries-net_asset_equity_id}}',
            '{{%dv_accounting_entries}}',
            'net_asset_equity_id',
            '{{%net_asset_equity}}',
            'id',
            'CASCADE'
        );

        // creates index for column `chart_of_account_id`
        $this->createIndex(
            '{{%idx-dv_accounting_entries-chart_of_account_id}}',
            '{{%dv_accounting_entries}}',
            'chart_of_account_id'
        );

        // add foreign key for table `{{%chart_of_accounts}}`
        $this->addForeignKey(
            '{{%fk-dv_accounting_entries-chart_of_account_id}}',
            '{{%dv_accounting_entries}}',
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
        // drops foreign key for table `{{%dv_aucs}}`
        $this->dropForeignKey(
            '{{%fk-dv_accounting_entries-dv_aucs_id}}',
            '{{%dv_accounting_entries}}'
        );

        // drops index for column `dv_aucs_id`
        $this->dropIndex(
            '{{%idx-dv_accounting_entries-dv_aucs_id}}',
            '{{%dv_accounting_entries}}'
        );

        // drops foreign key for table `{{%cash_flow}}`
        $this->dropForeignKey(
            '{{%fk-dv_accounting_entries-cashflow_id}}',
            '{{%dv_accounting_entries}}'
        );

        // drops index for column `cashflow_id`
        $this->dropIndex(
            '{{%idx-dv_accounting_entries-cashflow_id}}',
            '{{%dv_accounting_entries}}'
        );

        // drops foreign key for table `{{%net_asset_equity}}`
        $this->dropForeignKey(
            '{{%fk-dv_accounting_entries-net_asset_equity_id}}',
            '{{%dv_accounting_entries}}'
        );

        // drops index for column `net_asset_equity_id`
        $this->dropIndex(
            '{{%idx-dv_accounting_entries-net_asset_equity_id}}',
            '{{%dv_accounting_entries}}'
        );

        // drops foreign key for table `{{%chart_of_accounts}}`
        $this->dropForeignKey(
            '{{%fk-dv_accounting_entries-chart_of_account_id}}',
            '{{%dv_accounting_entries}}'
        );

        // drops index for column `chart_of_account_id`
        $this->dropIndex(
            '{{%idx-dv_accounting_entries-chart_of_account_id}}',
            '{{%dv_accounting_entries}}'
        );

        $this->dropTable('{{%dv_accounting_entries}}');
    }
}
