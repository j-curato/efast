<?php

use yii\db\Migration;

/**
 * Class m231116_060721_add_fk_approved_by_and_fk_officer_in_charge_in_iar_transmittal_table
 */
class m231116_060721_add_fk_approved_by_and_fk_officer_in_charge_in_iar_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('iar_transmittal', 'fk_approved_by', $this->bigInteger());
        $this->addColumn('iar_transmittal', 'fk_officer_in_charge', $this->bigInteger());
        // fk_approved_by
        $this->createIndex('idx-iar_transmittal-fk_approved_by', 'iar_transmittal', 'fk_approved_by');
        $this->addForeignKey('fk-iar_transmittal-fk_approved_by', 'iar_transmittal', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT');
        // fk_officer_in_charge
        $this->createIndex('idx-iar_transmittal-fk_officer_in_charge', 'iar_transmittal', 'fk_officer_in_charge');
        $this->addForeignKey('fk-iar_transmittal-fk_officer_in_charge', 'iar_transmittal', 'fk_officer_in_charge', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // fk_approved_by
        $this->dropForeignKey('fk-iar_transmittal-fk_approved_by', 'iar_transmittal');
        $this->dropIndex('idx-iar_transmittal-fk_approved_by', 'iar_transmittal');
        // fk_officer_in_charge
        $this->dropForeignKey('fk-iar_transmittal-fk_officer_in_charge', 'iar_transmittal');
        $this->dropIndex('idx-iar_transmittal-fk_officer_in_charge', 'iar_transmittal');

        $this->dropColumn('iar_transmittal', 'fk_approved_by');
        $this->dropColumn('iar_transmittal', 'fk_officer_in_charge');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231116_060721_add_fk_approved_by_and_fk_officer_in_charge_in_iar_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
