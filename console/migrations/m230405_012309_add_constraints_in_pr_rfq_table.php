<?php

use yii\db\Migration;

/**
 * Class m230405_012309_add_constraints_in_pr_rfq_table
 */
class m230405_012309_add_constraints_in_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->createIndex('idx-pr_purchase_request_id', 'pr_rfq', 'pr_purchase_request_id');
        $this->createIndex('idx-employee_id', 'pr_rfq', 'employee_id');

        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->addForeignKey('fk-rfq-pr_purchase_request_id', 'pr_rfq', 'pr_purchase_request_id', 'pr_purchase_request', 'id', 'RESTRICT');
        $this->addForeignKey('fk-rfq-employee_id', 'pr_rfq', 'employee_id', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rfq-pr_purchase_request_id', 'pr_rfq');
        $this->dropForeignKey('fk-rfq-employee_id', 'pr_rfq');

        $this->dropIndex('idx-pr_purchase_request_id', 'pr_rfq');
        $this->dropIndex('idx-employee_id', 'pr_rfq');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230405_012309_add_constraints_in_pr_rfq_table cannot be reverted.\n";

        return false;
    }
    */
}
