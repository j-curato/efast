<?php

use yii\db\Migration;

/**
 * Class m210429_011501_remove_dv_aucs_entries_id_in_cash_disbursement_table
 */
class m210429_011501_remove_dv_aucs_entries_id_in_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('cash_disbursement','dv_aucs_entries_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('cash_disbursement','dv_aucs_entries_id',$this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210429_011501_remove_dv_aucs_entries_id_in_cash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}
