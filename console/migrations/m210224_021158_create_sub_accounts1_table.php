<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sub_accounts1}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%chart_of_accounts}}`
 */
class m210224_021158_create_sub_accounts1_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sub_accounts1}}', [
            'id' => $this->primaryKey(),
            'chart_of_account_id' => $this->integer()->notNull(),
            'object_code' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
        ]);

        // creates index for column `chart_of_account_id`
        $this->createIndex(
            '{{%idx-sub_accounts1-chart_of_account_id}}',
            '{{%sub_accounts1}}',
            'chart_of_account_id'
        );

        // add foreign key for table `{{%chart_of_accounts}}`
        $this->addForeignKey(
            '{{%fk-sub_accounts1-chart_of_account_id}}',
            '{{%sub_accounts1}}',
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
            '{{%fk-sub_accounts1-chart_of_account_id}}',
            '{{%sub_accounts1}}'
        );

        // drops index for column `chart_of_account_id`
        $this->dropIndex(
            '{{%idx-sub_accounts1-chart_of_account_id}}',
            '{{%sub_accounts1}}'
        );

        $this->dropTable('{{%sub_accounts1}}');
    }
}
