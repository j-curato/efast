<?php

use yii\db\Migration;

/**
 * Class m220119_040332_alter_employee_id_in_employee_table
 */
class m220119_040332_alter_employee_id_in_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%employee}}', 'employee_id', $this->bigInteger() . ' NOT NULL ');
        $this->addColumn('employee', 'employee_number', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%employee}}', 'employee_id', $this->integer() . ' NOT NULL ');
        $this->dropColumn('employee', 'employee_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220119_040332_alter_employee_id_in_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
