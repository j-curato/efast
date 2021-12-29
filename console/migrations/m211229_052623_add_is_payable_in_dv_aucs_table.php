<?php

use yii\db\Migration;

/**
 * Class m211229_052623_add_is_payable_in_dv_aucs_table
 */
class m211229_052623_add_is_payable_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('dv_aucs','is_payable',$this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','is_payable');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211229_052623_add_is_payable_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
