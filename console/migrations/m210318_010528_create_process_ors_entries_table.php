<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%process_ors_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%chart_of_accounts}}`
 * - `{{%process_ors}}`
 */
class m210318_010528_create_process_ors_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%process_ors_entries}}', [
            'id' => $this->primaryKey(),
            'chart_of_account_id' => $this->integer()->notNull(),
            'process_ors_id' => $this->integer()->notNull(),
            'amount' => $this->float()->notNull(),
        ]);

        // creates index for column `chart_of_account_id`
        $this->createIndex(
            '{{%idx-process_ors_entries-chart_of_account_id}}',
            '{{%process_ors_entries}}',
            'chart_of_account_id'
        );

        // add foreign key for table `{{%chart_of_accounts}}`
        $this->addForeignKey(
            '{{%fk-process_ors_entries-chart_of_account_id}}',
            '{{%process_ors_entries}}',
            'chart_of_account_id',
            '{{%chart_of_accounts}}',
            'id',
            'CASCADE'
        );

        // creates index for column `process_ors_id`
        $this->createIndex(
            '{{%idx-process_ors_entries-process_ors_id}}',
            '{{%process_ors_entries}}',
            'process_ors_id'
        );

        // add foreign key for table `{{%process_ors}}`
        $this->addForeignKey(
            '{{%fk-process_ors_entries-process_ors_id}}',
            '{{%process_ors_entries}}',
            'process_ors_id',
            '{{%process_ors}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%chart_of_accounts}}`
        $this->dropForeignKey(
            '{{%fk-process_ors_entries-chart_of_account_id}}',
            '{{%process_ors_entries}}'
        );

        // drops index for column `chart_of_account_id`
        $this->dropIndex(
            '{{%idx-process_ors_entries-chart_of_account_id}}',
            '{{%process_ors_entries}}'
        );

        // drops foreign key for table `{{%process_ors}}`
        $this->dropForeignKey(
            '{{%fk-process_ors_entries-process_ors_id}}',
            '{{%process_ors_entries}}'
        );

        // drops index for column `process_ors_id`
        $this->dropIndex(
            '{{%idx-process_ors_entries-process_ors_id}}',
            '{{%process_ors_entries}}'
        );

        $this->dropTable('{{%process_ors_entries}}');
    }
}
