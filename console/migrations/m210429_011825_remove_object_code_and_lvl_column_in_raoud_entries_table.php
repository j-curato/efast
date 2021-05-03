<?php

use yii\db\Migration;

/**
 * Class m210429_011825_remove_object_code_and_lvl_column_in_raoud_entries_table
 */
class m210429_011825_remove_object_code_and_lvl_column_in_raoud_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('raoud_entries','object_code');
        $this->dropColumn('raoud_entries','lvl');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('raoud_entries','object_code',$this->string());
        $this->addColumn('raoud_entries','lvl',$this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210429_011825_remove_object_code_and_lvl_column_in_raoud_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
