<?php

use yii\db\Migration;

/**
 * Class m220623_032955_add_object_code_in_dv_aucs_table
 */
class m220623_032955_add_object_code_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','object_code',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','object_code');
        ;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220623_032955_add_object_code_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
