<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_transmittal_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%po_transmittal}}`
 * - `{{%liquidation}}`
 */
class m210719_082408_create_po_transmittal_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_transmittal_entries}}', [
            'id' => $this->primaryKey(),
            'po_transmittal_number' => $this->string(),
            'liquidation_id' => $this->integer(),
        ]);

        // creates index for column `po_transmittal_number`
        $this->createIndex(
            '{{%idx-po_transmittal_entries-po_transmittal_number}}',
            '{{%po_transmittal_entries}}',
            'po_transmittal_number'
        );

        // add foreign key for table `{{%po_transmittal}}`
        $this->addForeignKey(
            '{{%fk-po_transmittal_entries-po_transmittal_number}}',
            '{{%po_transmittal_entries}}',
            'po_transmittal_number',
            '{{%po_transmittal}}',
            'transmittal_number',
            'CASCADE'
        );

        // creates index for column `liquidation_id`
        $this->createIndex(
            '{{%idx-po_transmittal_entries-liquidation_id}}',
            '{{%po_transmittal_entries}}',
            'liquidation_id'
        );

        // add foreign key for table `{{%liquidation}}`
        $this->addForeignKey(
            '{{%fk-po_transmittal_entries-liquidation_id}}',
            '{{%po_transmittal_entries}}',
            'liquidation_id',
            '{{%liquidation}}',
            'id',
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
            '{{%fk-po_transmittal_entries-po_transmittal_number}}',
            '{{%po_transmittal_entries}}'
        );

        // drops index for column `po_transmittal_number`
        $this->dropIndex(
            '{{%idx-po_transmittal_entries-po_transmittal_number}}',
            '{{%po_transmittal_entries}}'
        );

        // drops foreign key for table `{{%liquidation}}`
        $this->dropForeignKey(
            '{{%fk-po_transmittal_entries-liquidation_id}}',
            '{{%po_transmittal_entries}}'
        );

        // drops index for column `liquidation_id`
        $this->dropIndex(
            '{{%idx-po_transmittal_entries-liquidation_id}}',
            '{{%po_transmittal_entries}}'
        );

        $this->dropTable('{{%po_transmittal_entries}}');
    }
}
