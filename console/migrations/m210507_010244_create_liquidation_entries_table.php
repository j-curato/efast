<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%liquidation_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%liquidation}}`
 * - `{{%chart_of_accounts}}`
 * - `{{%advances}}`
 */
class m210507_010244_create_liquidation_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%liquidation_entries}}', [
            'id' => $this->primaryKey(),
            'liquidation_id' => $this->integer(),
            'chart_of_account_id' => $this->integer(),
            'advances_id' => $this->integer(),
            'withdrawals'=>$this->decimal(10,2),
            'vat_nonvat'=>$this->decimal(10,2),
            'expanded_tax'=>$this->decimal(10,2)
        ]);

        // creates index for column `liquidation_id`
        $this->createIndex(
            '{{%idx-liquidation_entries-liquidation_id}}',
            '{{%liquidation_entries}}',
            'liquidation_id'
        );

        // add foreign key for table `{{%liquidation}}`
        $this->addForeignKey(
            '{{%fk-liquidation_entries-liquidation_id}}',
            '{{%liquidation_entries}}',
            'liquidation_id',
            '{{%liquidation}}',
            'id',
            'NO ACTION'
        );

        // creates index for column `chart_of_account_id`
        $this->createIndex(
            '{{%idx-liquidation_entries-chart_of_account_id}}',
            '{{%liquidation_entries}}',
            'chart_of_account_id'
        );

        // add foreign key for table `{{%chart_of_accounts}}`
        $this->addForeignKey(
            '{{%fk-liquidation_entries-chart_of_account_id}}',
            '{{%liquidation_entries}}',
            'chart_of_account_id',
            '{{%chart_of_accounts}}',
            'id',
            'NO ACTION'
        );

        // creates index for column `advances_id`
        $this->createIndex(
            '{{%idx-liquidation_entries-advances_id}}',
            '{{%liquidation_entries}}',
            'advances_id'
        );

        // add foreign key for table `{{%advances}}`
        $this->addForeignKey(
            '{{%fk-liquidation_entries-advances_id}}',
            '{{%liquidation_entries}}',
            'advances_id',
            '{{%advances}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%liquidation}}`
        $this->dropForeignKey(
            '{{%fk-liquidation_entries-liquidation_id}}',
            '{{%liquidation_entries}}'
        );

        // drops index for column `liquidation_id`
        $this->dropIndex(
            '{{%idx-liquidation_entries-liquidation_id}}',
            '{{%liquidation_entries}}'
        );

        // drops foreign key for table `{{%chart_of_accounts}}`
        $this->dropForeignKey(
            '{{%fk-liquidation_entries-chart_of_account_id}}',
            '{{%liquidation_entries}}'
        );

        // drops index for column `chart_of_account_id`
        $this->dropIndex(
            '{{%idx-liquidation_entries-chart_of_account_id}}',
            '{{%liquidation_entries}}'
        );

        // drops foreign key for table `{{%advances}}`
        $this->dropForeignKey(
            '{{%fk-liquidation_entries-advances_id}}',
            '{{%liquidation_entries}}'
        );

        // drops index for column `advances_id`
        $this->dropIndex(
            '{{%idx-liquidation_entries-advances_id}}',
            '{{%liquidation_entries}}'
        );

        $this->dropTable('{{%liquidation_entries}}');
    }
}
