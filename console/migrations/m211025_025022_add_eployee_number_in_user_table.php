<?php

use yii\db\Migration;

/**
 * Class m211025_025022_add_eployee_number_in_user_table
 */
class m211025_025022_add_eployee_number_in_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'employee_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'employee_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211025_025022_add_eployee_number_in_user_table cannot be reverted.\n";

        return false;
    }
    */
}
