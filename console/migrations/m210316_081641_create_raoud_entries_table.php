<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%raoud_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%raouds}}`
 * - `{{%chart_of_accounts}}`
 */
class m210316_081641_create_raoud_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%raoud_entries}}', [
            'id' => $this->primaryKey(),
            'raoud_id' => $this->integer(),
            'chart_of_account_id' => $this->integer(),
            'amount'=>$this->float()
        ]);

        // creates index for column `raoud_id`
        $this->createIndex(
            '{{%idx-raoud_entries-raoud_id}}',
            '{{%raoud_entries}}',
            'raoud_id'
        );

        // add foreign key for table `{{%raouds}}`
        $this->addForeignKey(
            '{{%fk-raoud_entries-raoud_id}}',
            '{{%raoud_entries}}',
            'raoud_id',
            '{{%raouds}}',
            'id',
            'CASCADE'
        );

        // creates index for column `chart_of_account_id`
        $this->createIndex(
            '{{%idx-raoud_entries-chart_of_account_id}}',
            '{{%raoud_entries}}',
            'chart_of_account_id'
        );

        // add foreign key for table `{{%chart_of_accounts}}`
        $this->addForeignKey(
            '{{%fk-raoud_entries-chart_of_account_id}}',
            '{{%raoud_entries}}',
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
        // drops foreign key for table `{{%raouds}}`
        $this->dropForeignKey(
            '{{%fk-raoud_entries-raoud_id}}',
            '{{%raoud_entries}}'
        );

        // drops index for column `raoud_id`
        $this->dropIndex(
            '{{%idx-raoud_entries-raoud_id}}',
            '{{%raoud_entries}}'
        );

        // drops foreign key for table `{{%chart_of_accounts}}`
        $this->dropForeignKey(
            '{{%fk-raoud_entries-chart_of_account_id}}',
            '{{%raoud_entries}}'
        );

        // drops index for column `chart_of_account_id`
        $this->dropIndex(
            '{{%idx-raoud_entries-chart_of_account_id}}',
            '{{%raoud_entries}}'
        );

        $this->dropTable('{{%raoud_entries}}');
    }
}
