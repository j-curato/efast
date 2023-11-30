<?php

use yii\db\Migration;

/**
 * Class m231130_032605_add_constraints_in_po_transmittal_entries_table
 */
class m231130_032605_add_constraints_in_po_transmittal_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'idx-po_transmittal_entries-fk_po_transmittal_id',
            'po_transmittal_entries',
            'fk_po_transmittal_id'
        );
        $this->addForeignKey(
            'fk-po_transmittal_entries-fk_po_transmittal_id',
            'po_transmittal_entries',
            'fk_po_transmittal_id',
            'po_transmittal',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->dropForeignKey('fk-po_transmittal_entries-fk_po_transmittal_id', 'po_transmittal_entries');
        // $this->dropIndex('idx-po_transmittal_entries-fk_po_transmittal_id', 'po_transmittal_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231130_032605_add_constraints_in_po_transmittal_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
