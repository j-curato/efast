<?php

use yii\db\Migration;

/**
 * Class m210415_092858_add_date_to_process_ors_table
 */
class m210415_092858_add_date_to_process_ors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('process_ors','date',$this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('process_ors','date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210415_092858_add_date_to_process_ors_table cannot be reverted.\n";

        return false;
    }
    */
}
