<?php

use yii\db\Migration;

/**
 * Class m220314_065659_add_new_object_code_in_liquidation_entries_table
 */
class m220314_065659_add_new_object_code_in_liquidation_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation_entries','new_object_code',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation_entries','new_object_code');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220314_065659_add_new_object_code_in_liquidation_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
