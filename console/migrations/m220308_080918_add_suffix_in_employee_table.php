<?php

use yii\db\Migration;

/**
 * Class m220308_080918_add_suffix_in_employee_table
 */
class m220308_080918_add_suffix_in_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee','suffix',$this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('employee','suffix');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220308_080918_add_suffix_in_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
