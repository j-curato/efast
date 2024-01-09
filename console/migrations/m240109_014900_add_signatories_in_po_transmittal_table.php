<?php

use yii\db\Migration;

/**
 * Class m240109_014900_add_signatories_in_po_transmittal_table
 */
class m240109_014900_add_signatories_in_po_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal', 'fk_approved_by', $this->bigInteger());
        $this->createIndex('idx-po_transmittal-fk_approved_by', 'po_transmittal', 'fk_approved_by');
        $this->addForeignKey(
            'fk-po_transmittal-fk_approved_by',
            'po_transmittal',
            'fk_approved_by',
            'employee',
            'employee_id',
            'RESTRICT'
        );
        $this->addColumn('po_transmittal', 'fk_officer_in_charge', $this->bigInteger());
        $this->createIndex('idx-po_transmittal-fk_officer_in_charge', 'po_transmittal', 'fk_officer_in_charge');
        $this->addForeignKey(
            'fk-po_transmittal-fk_officer_in_charge',
            'po_transmittal',
            'fk_officer_in_charge',
            'employee',
            'employee_id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-po_transmittal-fk_approved_by',
            'po_transmittal'
        );
        $this->dropIndex('idx-po_transmittal-fk_approved_by', 'po_transmittal');
        $this->dropColumn('po_transmittal', 'fk_approved_by');
        $this->dropForeignKey(
            'fk-po_transmittal-fk_officer_in_charge',
            'po_transmittal'
        );
        $this->dropIndex('idx-po_transmittal-fk_officer_in_charge', 'po_transmittal');
        $this->dropColumn('po_transmittal', 'fk_officer_in_charge');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240109_014900_add_signatories_in_po_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
