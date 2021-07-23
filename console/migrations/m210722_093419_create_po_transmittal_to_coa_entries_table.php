<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_transmittal_to_coa_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%po_transmittal}}`
 * - `{{%po_transmittal_to_coa}}`
 */
class m210722_093419_create_po_transmittal_to_coa_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_transmittal_to_coa_entries}}', [
            'id' => $this->primaryKey(),
            'po_transmittal_number' => $this->string(),
            'po_transmittal_to_coa_number' => $this->string(),
        ]);

        // creates index for column `po_transmittal_number`
        $this->createIndex(
            '{{%idx-po_transmittal_to_coa_entries-po_transmittal_number}}',
            '{{%po_transmittal_to_coa_entries}}',
            'po_transmittal_number'
        );

        // add foreign key for table `{{%po_transmittal}}`
        $this->addForeignKey(
            '{{%fk-po_transmittal_to_coa_entries-po_transmittal_number}}',
            '{{%po_transmittal_to_coa_entries}}',
            'po_transmittal_number',
            '{{%po_transmittal}}',
            'transmittal_number',
            'CASCADE'
        );

        // creates index for column `po_transmittal_to_coa_number`
        $this->createIndex(
            '{{%idx-po_transmittal_to_coa_entries-po_transmittal_to_coa_number}}',
            '{{%po_transmittal_to_coa_entries}}',
            'po_transmittal_to_coa_number'
        );

        // add foreign key for table `{{%po_transmittal_to_coa}}`
        $this->addForeignKey(
            '{{%fk-po_transmittal_to_coa_entries-po_transmittal_to_coa_number}}',
            '{{%po_transmittal_to_coa_entries}}',
            'po_transmittal_to_coa_number',
            '{{%po_transmittal_to_coa}}',
            'transmittal_number',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%po_transmittal}}`
        $this->dropForeignKey(
            '{{%fk-po_transmittal_to_coa_entries-po_transmittal_number}}',
            '{{%po_transmittal_to_coa_entries}}'
        );

        // drops index for column `po_transmittal_number`
        $this->dropIndex(
            '{{%idx-po_transmittal_to_coa_entries-po_transmittal_number}}',
            '{{%po_transmittal_to_coa_entries}}'
        );

        // drops foreign key for table `{{%po_transmittal_to_coa}}`
        $this->dropForeignKey(
            '{{%fk-po_transmittal_to_coa_entries-po_transmittal_to_coa_number}}',
            '{{%po_transmittal_to_coa_entries}}'
        );

        // drops index for column `po_transmittal_to_coa_number`
        $this->dropIndex(
            '{{%idx-po_transmittal_to_coa_entries-po_transmittal_to_coa_number}}',
            '{{%po_transmittal_to_coa_entries}}'
        );

        $this->dropTable('{{%po_transmittal_to_coa_entries}}');
    }
}
