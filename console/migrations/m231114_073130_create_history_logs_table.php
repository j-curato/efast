<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%history_logs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m231114_073130_create_history_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%history_logs}}', [
            'id' => $this->primaryKey(),
            'server_name' => $this->string()->notNull(),
            'table_name' => $this->string()->notNull(),
            'row_id' => $this->string()->notNull(),
            'attribute_name' => $this->string()->notNull(),
            'old_value' => $this->text()->notNull(),
            'new_value' => $this->text()->notNull(),
            'fk_changed_by' => $this->bigInteger()->notNull(),
            'changed_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('history_logs', 'id', $this->bigInteger());
        // creates index for column `fk_changed_by`
        $this->createIndex(
            '{{%idx-history_logs-fk_changed_by}}',
            '{{%history_logs}}',
            'fk_changed_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-history_logs-fk_changed_by}}',
            '{{%history_logs}}',
            'fk_changed_by',
            '{{%user}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-history_logs-fk_changed_by}}',
            '{{%history_logs}}'
        );

        // drops index for column `fk_changed_by`
        $this->dropIndex(
            '{{%idx-history_logs-fk_changed_by}}',
            '{{%history_logs}}'
        );

        $this->dropTable('{{%history_logs}}');
    }
}
