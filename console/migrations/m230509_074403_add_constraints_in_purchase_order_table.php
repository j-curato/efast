<?php

use yii\db\Migration;

/**
 * Class m230509_074403_add_constraints_in_purchase_order_table
 */
class m230509_074403_add_constraints_in_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        $this->createIndex('idx-po-fk_contract_type_id', 'pr_purchase_order', 'fk_contract_type_id');
        $this->createIndex('idx-po-fk_mode_of_procurement_id', 'pr_purchase_order', 'fk_mode_of_procurement_id');
        $this->createIndex('idx-po-fk_pr_aoq_id', 'pr_purchase_order', 'fk_pr_aoq_id');
        $this->createIndex('idx-po-fk_auth_official', 'pr_purchase_order', 'fk_auth_official');
        $this->createIndex('idx-po-fk_accounting_unit', 'pr_purchase_order', 'fk_accounting_unit');
        $this->createIndex('idx-po-fk_requested_by', 'pr_purchase_order', 'fk_requested_by');
        $this->createIndex('idx-po-fk_inspected_by', 'pr_purchase_order', 'fk_inspected_by');

        $this->addForeignKey('fk-po-fk_contract_type_id', 'pr_purchase_order', 'fk_contract_type_id', 'pr_contract_type', 'id', 'RESTRICT');
        $this->addForeignKey('fk-po-fk_mode_of_procurement_id', 'pr_purchase_order', 'fk_mode_of_procurement_id', 'pr_mode_of_procurement', 'id', 'RESTRICT');
        // $this->addForeignKey('fk-po-fk_pr_aoq_id', 'pr_purchase_order', 'fk_pr_aoq_id', 'pr_aoq', 'id', 'RESTRICT');
        $this->addForeignKey('fk-po-fk_auth_official', 'pr_purchase_order', 'fk_auth_official', 'employee', 'employee_id', 'RESTRICT');
        $this->addForeignKey('fk-po-fk_accounting_unit', 'pr_purchase_order', 'fk_accounting_unit', 'employee', 'employee_id', 'RESTRICT');
        $this->addForeignKey('fk-po-fk_requested_by', 'pr_purchase_order', 'fk_requested_by', 'employee', 'employee_id', 'RESTRICT');
        $this->addForeignKey('fk-po-fk_inspected_by', 'pr_purchase_order', 'fk_inspected_by', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-po-fk_contract_type_id', 'pr_purchase_order');
        $this->dropForeignKey('fk-po-fk_mode_of_procurement_id', 'pr_purchase_order');
        $this->dropForeignKey('fk-po-fk_auth_official', 'pr_purchase_order');
        $this->dropForeignKey('fk-po-fk_accounting_unit', 'pr_purchase_order');
        $this->dropForeignKey('fk-po-fk_requested_by', 'pr_purchase_order');
        $this->dropForeignKey('fk-po-fk_inspected_by', 'pr_purchase_order');

        $this->dropIndex('idx-po-fk_contract_type_id', 'pr_purchase_order');
        $this->dropIndex('idx-po-fk_mode_of_procurement_id', 'pr_purchase_order');
        $this->dropIndex('idx-po-fk_pr_aoq_id', 'pr_purchase_order');
        $this->dropIndex('idx-po-fk_auth_official', 'pr_purchase_order');
        $this->dropIndex('idx-po-fk_accounting_unit', 'pr_purchase_order');
        $this->dropIndex('idx-po-fk_requested_by', 'pr_purchase_order');
        $this->dropIndex('idx-po-fk_inspected_by', 'pr_purchase_order');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230509_074403_add_constraints_in_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
