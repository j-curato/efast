<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chart_of_accounts}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%major_accounts}}`
 * - `{{%sub_major_accounts}}`
 */
class m210201_032647_create_chart_of_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chart_of_accounts}}', [
            'id' => $this->primaryKey(),
            'uacs' => $this->string(30)->notNull(),
            'general_ledger' => $this->string(255)->notNull(),
            'major_account_id' => $this->integer()->notNull(),
            'sub_major_account' => $this->integer()->notNull(),
            'account_group' => $this->string(255)->notNull(),
            'current_noncurrent' => $this->string(255)->notNull(),
            'enable_disable' => $this->string(255)->notNull(),
        ]);

        // creates index for column `major_account_id`
        $this->createIndex(
            '{{%idx-chart_of_accounts-major_account_id}}',
            '{{%chart_of_accounts}}',
            'major_account_id'
        );

        // add foreign key for table `{{%major_accounts}}`
        $this->addForeignKey(
            '{{%fk-chart_of_accounts-major_account_id}}',
            '{{%chart_of_accounts}}',
            'major_account_id',
            '{{%major_accounts}}',
            'id',
            'CASCADE'
        );

        // creates index for column `sub_major_account`
        $this->createIndex(
            '{{%idx-chart_of_accounts-sub_major_account}}',
            '{{%chart_of_accounts}}',
            'sub_major_account'
        );

        // add foreign key for table `{{%sub_major_accounts}}`
        $this->addForeignKey(
            '{{%fk-chart_of_accounts-sub_major_account}}',
            '{{%chart_of_accounts}}',
            'sub_major_account',
            '{{%sub_major_accounts}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%major_accounts}}`
        $this->dropForeignKey(
            '{{%fk-chart_of_accounts-major_account_id}}',
            '{{%chart_of_accounts}}'
        );

        // drops index for column `major_account_id`
        $this->dropIndex(
            '{{%idx-chart_of_accounts-major_account_id}}',
            '{{%chart_of_accounts}}'
        );

        // drops foreign key for table `{{%sub_major_accounts}}`
        $this->dropForeignKey(
            '{{%fk-chart_of_accounts-sub_major_account}}',
            '{{%chart_of_accounts}}'
        );

        // drops index for column `sub_major_account`
        $this->dropIndex(
            '{{%idx-chart_of_accounts-sub_major_account}}',
            '{{%chart_of_accounts}}'
        );

        $this->dropTable('{{%chart_of_accounts}}');
    }
}
