<?php

use yii\db\Migration;

/**
 * Class m210303_134625_add_object_code_to_jev_preparation_table
 */
class m210303_134625_add_object_code_to_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_accounting_entries','lvl',$this->integer());
        $this->addColumn('jev_accounting_entries','object_code',$this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_accounting_entries','object_code');
        $this->dropColumn('jev_accounting_entries','lvl');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210303_134625_add_object_code_to_jev_preparation_table cannot be reverted.\n";

        return false;
    }
    */
}
