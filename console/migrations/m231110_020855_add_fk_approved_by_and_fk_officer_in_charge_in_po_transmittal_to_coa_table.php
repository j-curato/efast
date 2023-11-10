<?php

use yii\db\Migration;

/**
 * Class m231110_020855_add_fk_approved_by_and_fk_officer_in_charge_in_po_transmittal_to_coa_table
 */
class m231110_020855_add_fk_approved_by_and_fk_officer_in_charge_in_po_transmittal_to_coa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal_to_coa', 'fk_approved_by', $this->bigInteger());
        $this->addColumn('po_transmittal_to_coa', 'fk_officer_in_charge', $this->bigInteger());
        // fk_approved_by
        $this->createIndex('idx-po_transmittal_to_coa-fk_approved_by', 'po_transmittal_to_coa', 'fk_approved_by');
        $this->addForeignKey('fk-po_transmittal_to_coa-fk_approved_by', 'po_transmittal_to_coa', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT');
        // fk_officer_in_charge
        $this->createIndex('idx-po_transmittal_to_coa-fk_officer_in_charge', 'po_transmittal_to_coa', 'fk_officer_in_charge');
        $this->addForeignKey('fk-po_transmittal_to_coa-fk_officer_in_charge', 'po_transmittal_to_coa', 'fk_officer_in_charge', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // fk_approved_by
        $this->dropForeignKey('fk-po_transmittal_to_coa-fk_approved_by', 'po_transmittal_to_coa');
        $this->dropIndex('idx-po_transmittal_to_coa-fk_approved_by', 'po_transmittal_to_coa');
        // fk_officer_in_charge
        $this->dropForeignKey('fk-po_transmittal_to_coa-fk_officer_in_charge', 'po_transmittal_to_coa');
        $this->dropIndex('idx-po_transmittal_to_coa-fk_officer_in_charge', 'po_transmittal_to_coa');

        $this->dropColumn('po_transmittal_to_coa', 'fk_approved_by');
        $this->dropColumn('po_transmittal_to_coa', 'fk_officer_in_charge');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231110_020855_add_fk_approved_by_and_fk_officer_in_charge_in_po_transmittal_to_coa_table cannot be reverted.\n";

        return false;
    }
    */
}
