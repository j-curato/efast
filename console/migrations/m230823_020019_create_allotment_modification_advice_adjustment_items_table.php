<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%allotment_modification_advice_adjustment_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%record_allotment_entries}}`
 * - `{{%allotment_modification_advice}}`
 */
class m230823_020019_create_allotment_modification_advice_adjustment_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%allotment_modification_advice_adjustment_items}}', [
            'id' => $this->primaryKey(),
            'fk_record_allotment_entry_id' => $this->integer()->notNull(),
            'fk_allotment_modification_advice_id' => $this->bigInteger()->notNull(),
            'amount' => $this->decimal(15, 2)->notNull(),
            'is_deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_record_allotment_entry_id`
        $this->createIndex(
            '{{%idx-maf-adjust-fk_record_allotment_entry_id}}',
            '{{%allotment_modification_advice_adjustment_items}}',
            'fk_record_allotment_entry_id'
        );

        // add foreign key for table `{{%record_allotment_entries}}`
        $this->addForeignKey(
            '{{%fk-maf-adjust-fk_record_allotment_entry_id}}',
            '{{%allotment_modification_advice_adjustment_items}}',
            'fk_record_allotment_entry_id',
            '{{%record_allotment_entries}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_allotment_modification_advice_id`
        $this->createIndex(
            '{{%idx-maf-adjust-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_adjustment_items}}',
            'fk_allotment_modification_advice_id'
        );

        // add foreign key for table `{{%allotment_modification_advice}}`
        $this->addForeignKey(
            '{{%fk-maf-adjust-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_adjustment_items}}',
            'fk_allotment_modification_advice_id',
            '{{%allotment_modification_advice}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%record_allotment_entries}}`
        $this->dropForeignKey(
            '{{%fk-maf-adjust-fk_record_allotment_entry_id}}',
            '{{%allotment_modification_advice_adjustment_items}}'
        );

        // drops index for column `fk_record_allotment_entry_id`
        $this->dropIndex(
            '{{%idx-maf-adjust-fk_record_allotment_entry_id}}',
            '{{%allotment_modification_advice_adjustment_items}}'
        );

        // drops foreign key for table `{{%allotment_modification_advice}}`
        $this->dropForeignKey(
            '{{%fk-maf-adjust-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_adjustment_items}}'
        );

        // drops index for column `fk_allotment_modification_advice_id`
        $this->dropIndex(
            '{{%idx-maf-adjust-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_adjustment_items}}'
        );

        $this->dropTable('{{%allotment_modification_advice_adjustment_items}}');
    }
}
