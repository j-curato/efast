<?php

use yii\db\Migration;

/**
 * Class m220512_014710_update_fk_auth_employee_and_accounting_employee_id_in_pr_purchase_order_table
 */
class m220512_014710_update_fk_auth_employee_and_accounting_employee_id_in_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->alterColumn('pr_purchase_order', 'fk_auth_official', $this->bigInteger());
        $this->alterColumn('pr_purchase_order', 'fk_accounting_unit', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220512_014710_update_fk_auth_employee_and_accounting_employee_id_in_pr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
