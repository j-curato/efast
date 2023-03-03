<?php

use yii\db\Migration;

/**
 * Class m230227_075203_alter_atributes_in_par_table
 */
class m230227_075203_alter_atributes_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('par', 'employee_id', 'fk_recieved_by');
        $this->renameColumn('par', 'actual_user', 'fk_actual_user');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('par', 'fk_recieved_by', 'employee_id');
        $this->renameColumn('par', 'fk_actual_user', 'actual_user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230227_075203_alter_atributes_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
