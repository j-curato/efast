<?php

use yii\db\Migration;

/**
 * Class m231110_022721_add_fk_approved_by_and_fk_officer_in_charge_in_purchase_order_transmittal_table
 */
class m231110_022721_add_fk_approved_by_and_fk_officer_in_charge_in_purchase_order_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('purchase_order_transmittal', 'fk_approved_by', $this->bigInteger());
        $this->addColumn('purchase_order_transmittal', 'fk_officer_in_charge', $this->bigInteger());
        // fk_approved_by
        $this->createIndex('idx-purchase_order_transmittal-fk_approved_by', 'purchase_order_transmittal', 'fk_approved_by');
        $this->addForeignKey('fk-purchase_order_transmittal-fk_approved_by', 'purchase_order_transmittal', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT');
        // fk_officer_in_charge
        $this->createIndex('idx-purchase_order_transmittal-fk_officer_in_charge', 'purchase_order_transmittal', 'fk_officer_in_charge');
        $this->addForeignKey('fk-purchase_order_transmittal-fk_officer_in_charge', 'purchase_order_transmittal', 'fk_officer_in_charge', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // fk_approved_by
        $this->dropForeignKey('fk-purchase_order_transmittal-fk_approved_by', 'purchase_order_transmittal');
        $this->dropIndex('idx-purchase_order_transmittal-fk_approved_by', 'purchase_order_transmittal');
        // fk_officer_in_charge
        $this->dropForeignKey('fk-purchase_order_transmittal-fk_officer_in_charge', 'purchase_order_transmittal');
        $this->dropIndex('idx-purchase_order_transmittal-fk_officer_in_charge', 'purchase_order_transmittal');

        $this->dropColumn('purchase_order_transmittal', 'fk_approved_by');
        $this->dropColumn('purchase_order_transmittal', 'fk_officer_in_charge');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231110_022721_add_fk_approved_by_and_fk_officer_in_charge_in_purchase_order_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
