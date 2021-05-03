<?php

use yii\db\Migration;

/**
 * Class m210429_011145_add_is_cancelled_to_dv_aucs_table
 */
class m210429_011145_add_is_cancelled_to_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','is_cancelled',$this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','is_cancelled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210429_011145_add_is_cancelled_to_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
