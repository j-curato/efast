<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%record_allotment_adjustments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%record_allotments}}`
 * - `{{%record_allotment_entries}}`
 */
class m230906_091316_create_record_allotment_adjustments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%record_allotment_adjustments}}', [
            'id' => $this->primaryKey(),
            'fk_record_allotment_id' => $this->integer()->notNull(),
            'fk_record_allotment_entry_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `fk_record_allotment_id`
        $this->createIndex(
            '{{%idx-record_allotment_adjustments-fk_record_allotment_id}}',
            '{{%record_allotment_adjustments}}',
            'fk_record_allotment_id'
        );

        // add foreign key for table `{{%record_allotments}}`
        $this->addForeignKey(
            '{{%fk-record_allotment_adjustments-fk_record_allotment_id}}',
            '{{%record_allotment_adjustments}}',
            'fk_record_allotment_id',
            '{{%record_allotments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_record_allotment_entry_id`
        $this->createIndex(
            '{{%idx-record_allotment_adjustments-fk_record_allotment_entry_id}}',
            '{{%record_allotment_adjustments}}',
            'fk_record_allotment_entry_id'
        );

        // add foreign key for table `{{%record_allotment_entries}}`
        $this->addForeignKey(
            '{{%fk-record_allotment_adjustments-fk_record_allotment_entry_id}}',
            '{{%record_allotment_adjustments}}',
            'fk_record_allotment_entry_id',
            '{{%record_allotment_entries}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%record_allotments}}`
        $this->dropForeignKey(
            '{{%fk-record_allotment_adjustments-fk_record_allotment_id}}',
            '{{%record_allotment_adjustments}}'
        );

        // drops index for column `fk_record_allotment_id`
        $this->dropIndex(
            '{{%idx-record_allotment_adjustments-fk_record_allotment_id}}',
            '{{%record_allotment_adjustments}}'
        );

        // drops foreign key for table `{{%record_allotment_entries}}`
        $this->dropForeignKey(
            '{{%fk-record_allotment_adjustments-fk_record_allotment_entry_id}}',
            '{{%record_allotment_adjustments}}'
        );

        // drops index for column `fk_record_allotment_entry_id`
        $this->dropIndex(
            '{{%idx-record_allotment_adjustments-fk_record_allotment_entry_id}}',
            '{{%record_allotment_adjustments}}'
        );

        $this->dropTable('{{%record_allotment_adjustments}}');
    }
}
