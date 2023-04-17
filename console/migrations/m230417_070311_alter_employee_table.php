<?php

use yii\db\Migration;

/**
 * Class m230417_070311_alter_employee_table
 */
class m230417_070311_alter_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee','is_disabled',$this->boolean()->defaultValue(0));
        $this->dropColumn('employee','office');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('employee','is_disabled');
        $this->addColumn('employee','office',$this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230417_070311_alter_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
