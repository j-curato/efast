<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transmittal_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cash_disbursement}}`
 * - `{{%transmittal}}`
 */
class m210426_061200_create_transmittal_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transmittal_entries}}', [
            'id' => $this->primaryKey(),
            'cash_disbursement_id' => $this->integer(),
            'transmittal_id' => $this->integer(),
            
        ]);

        // creates index for column `cash_disbursement_id`
        $this->createIndex(
            '{{%idx-transmittal_entries-cash_disbursement_id}}',
            '{{%transmittal_entries}}',
            'cash_disbursement_id'
        );

        // add foreign key for table `{{%cash_disbursement}}`
        $this->addForeignKey(
            '{{%fk-transmittal_entries-cash_disbursement_id}}',
            '{{%transmittal_entries}}',
            'cash_disbursement_id',
            '{{%cash_disbursement}}',
            'id',
            'CASCADE'
        );

        // creates index for column `transmittal_id`
        $this->createIndex(
            '{{%idx-transmittal_entries-transmittal_id}}',
            '{{%transmittal_entries}}',
            'transmittal_id'
        );

        // add foreign key for table `{{%transmittal}}`
        $this->addForeignKey(
            '{{%fk-transmittal_entries-transmittal_id}}',
            '{{%transmittal_entries}}',
            'transmittal_id',
            '{{%transmittal}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%cash_disbursement}}`
        $this->dropForeignKey(
            '{{%fk-transmittal_entries-cash_disbursement_id}}',
            '{{%transmittal_entries}}'
        );

        // drops index for column `cash_disbursement_id`
        $this->dropIndex(
            '{{%idx-transmittal_entries-cash_disbursement_id}}',
            '{{%transmittal_entries}}'
        );

        // drops foreign key for table `{{%transmittal}}`
        $this->dropForeignKey(
            '{{%fk-transmittal_entries-transmittal_id}}',
            '{{%transmittal_entries}}'
        );

        // drops index for column `transmittal_id`
        $this->dropIndex(
            '{{%idx-transmittal_entries-transmittal_id}}',
            '{{%transmittal_entries}}'
        );

        $this->dropTable('{{%transmittal_entries}}');
    }
}
