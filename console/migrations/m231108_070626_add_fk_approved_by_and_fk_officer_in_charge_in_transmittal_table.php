<?php

use yii\db\Migration;

/**
 * Class m231108_070626_add_fk_approved_by_and_fk_officer_in_charge_in_transmittal_table
 */
class m231108_070626_add_fk_approved_by_and_fk_officer_in_charge_in_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transmittal', 'fk_approved_by', $this->bigInteger());
        $this->addColumn('transmittal', 'fk_officer_in_charge', $this->bigInteger());
        // fk_approved_by
        $this->createIndex('idx-transmittal-fk_approved_by', 'transmittal', 'fk_approved_by');
        $this->addForeignKey('fk-transmittal-fk_approved_by', 'transmittal', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT');
        // fk_officer_in_charge
        $this->createIndex('idx-transmittal-fk_officer_in_charge', 'transmittal', 'fk_officer_in_charge');
        $this->addForeignKey('fk-transmittal-fk_officer_in_charge', 'transmittal', 'fk_officer_in_charge', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // fk_approved_by
        $this->dropForeignKey('fk-transmittal-fk_approved_by', 'transmittal');
        $this->dropIndex('idx-transmittal-fk_approved_by', 'transmittal');
        // fk_officer_in_charge
        $this->dropForeignKey('fk-transmittal-fk_officer_in_charge', 'transmittal');
        $this->dropIndex('idx-transmittal-fk_officer_in_charge', 'transmittal');

        $this->dropColumn('transmittal', 'fk_approved_by');
        $this->dropColumn('transmittal', 'fk_officer_in_charge');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231108_070626_add_fk_approved_by_and_fk_officer_in_charge_in_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
