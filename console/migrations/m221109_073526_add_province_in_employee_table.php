<?php

use yii\db\Migration;

/**
 * Class m221109_073526_add_province_in_employee_table
 */
class m221109_073526_add_province_in_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee', 'province', $this->string()->defaultValue('ro'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('employee', 'province');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221109_073526_add_province_in_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
