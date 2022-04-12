<?php

use yii\db\Migration;

/**
 * Class m220412_053349_add_payroll_id_in_dv_aucs_table
 */
class m220412_053349_add_payroll_id_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs', 'payroll_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs', 'payroll_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220412_053349_add_payroll_id_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
