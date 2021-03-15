<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%record_allotment_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%chart_of_accounts}}`
 */
class m210315_030028_create_record_allotment_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%record_allotment_entries}}', [
            'id' => $this->primaryKey(),
            'chart_of_account_id' => $this->integer()->notNulll(),
            'amount' => $this->float()->notNull(),
        ]);

        // creates index for column `chart_of_account_id`
        $this->createIndex(
            '{{%idx-record_allotment_entries-chart_of_account_id}}',
            '{{%record_allotment_entries}}',
            'chart_of_account_id'
        );

        // add foreign key for table `{{%chart_of_accounts}}`
        $this->addForeignKey(
            '{{%fk-record_allotment_entries-chart_of_account_id}}',
            '{{%record_allotment_entries}}',
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
        // drops foreign key for table `{{%chart_of_accounts}}`
        $this->dropForeignKey(
            '{{%fk-record_allotment_entries-chart_of_account_id}}',
            '{{%record_allotment_entries}}'
        );

        // drops index for column `chart_of_account_id`
        $this->dropIndex(
            '{{%idx-record_allotment_entries-chart_of_account_id}}',
            '{{%record_allotment_entries}}'
        );

        $this->dropTable('{{%record_allotment_entries}}');
    }
}
