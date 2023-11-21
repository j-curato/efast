<?php

use yii\db\Migration;

/**
 * Class m231120_011303_add_mobile_number_in_employee_table
 */
class m231120_011303_add_mobile_number_in_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee', 'mobile_number', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('employee', 'mobile_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231120_011303_add_mobile_number_in_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
