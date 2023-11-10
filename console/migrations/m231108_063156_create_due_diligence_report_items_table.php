<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%due_diligence_report_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%due_diligence_reports}}`
 */
class m231108_063156_create_due_diligence_report_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%due_diligence_report_items}}', [
            'id' => $this->primaryKey(),
            'fk_due_diligence_report_id' => $this->bigInteger()->notNull(),
            'customer_name' => $this->string()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('due_diligence_report_items', 'id', $this->bigInteger());

        // creates index for column `fk_due_diligence_report_id`
        $this->createIndex(
            '{{%idx-due_diligence_report_items-fk_due_diligence_report_id}}',
            '{{%due_diligence_report_items}}',
            'fk_due_diligence_report_id'
        );

        // add foreign key for table `{{%due_diligence_reports}}`
        $this->addForeignKey(
            '{{%fk-due_diligence_report_items-fk_due_diligence_report_id}}',
            '{{%due_diligence_report_items}}',
            'fk_due_diligence_report_id',
            '{{%due_diligence_reports}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%due_diligence_reports}}`
        $this->dropForeignKey(
            '{{%fk-due_diligence_report_items-fk_due_diligence_report_id}}',
            '{{%due_diligence_report_items}}'
        );

        // drops index for column `fk_due_diligence_report_id`
        $this->dropIndex(
            '{{%idx-due_diligence_report_items-fk_due_diligence_report_id}}',
            '{{%due_diligence_report_items}}'
        );

        $this->dropTable('{{%due_diligence_report_items}}');
    }
}
