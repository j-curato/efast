<?php

use yii\db\Migration;

/**
 * Class m211105_054257_add_office_in_employee_table
 */
class m211105_054257_add_office_in_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee','office',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('employee','office');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211105_054257_add_office_in_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
