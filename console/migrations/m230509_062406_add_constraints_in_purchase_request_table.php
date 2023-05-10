<?php

use yii\db\Migration;

/**
 * Class m230509_062406_add_constraints_in_purchase_request_table
 */
class m230509_062406_add_constraints_in_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-pr-fk_office_id', 'pr_purchase_request', 'fk_office_id');
        $this->addForeignKey('fk-pr-fk_office_id', 'pr_purchase_request', 'fk_office_id', 'office', 'id', 'RESTRICT');
        $this->createIndex('idx-pr-fk_division_id', 'pr_purchase_request', 'fk_division_id');
        $this->addForeignKey('fk-pr-fk_division_id', 'pr_purchase_request', 'fk_division_id', 'divisions', 'id', 'RESTRICT');
        $this->createIndex('idx-pr-fk_division_program_unit_id', 'pr_purchase_request', 'fk_division_program_unit_id');
        $this->addForeignKey('fk-pr-fk_division_program_unit_id', 'pr_purchase_request', 'fk_division_program_unit_id', 'division_program_unit', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-pr-fk_office_id', 'pr_purchase_request');
        $this->dropForeignKey('fk-pr-fk_division_id', 'pr_purchase_request');
        $this->dropForeignKey('fk-pr-fk_division_program_unit_id', 'pr_purchase_request');
        $this->dropIndex('idx-pr-fk_office_id', 'pr_purchase_request');
        $this->dropIndex('idx-pr-fk_division_id', 'pr_purchase_request');
        $this->dropIndex('idx-pr-fk_division_program_unit_id', 'pr_purchase_request');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230509_062406_add_constraints_in_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
