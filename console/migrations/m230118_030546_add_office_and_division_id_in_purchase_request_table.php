<?php

use yii\db\Migration;

/**
 * Class m230118_030546_add_office_and_division_id_in_purchase_request_table
 */
class m230118_030546_add_office_and_division_id_in_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request', 'fk_office_id', $this->integer());
        $this->addColumn('pr_purchase_request', 'fk_division_id', $this->bigInteger());
        $this->addColumn('pr_purchase_request', 'fk_division_program_unit_id', $this->integer());
        $this->addColumn('pr_purchase_request', 'is_fixed_expense', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_request', 'fk_office_id');
        $this->dropColumn('pr_purchase_request', 'fk_division_id');
        $this->dropColumn('pr_purchase_request', 'fk_division_program_unit_id');
        $this->dropColumn('pr_purchase_request', 'is_fixed_expense');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230118_030546_add_office_and_division_id_in_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
