<?php

use yii\db\Migration;

/**
 * Class m211011_083213_add_cancel_parent_in_cash_disbursement_table
 */
class m211011_083213_add_cancel_parent_in_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_disbursement','parent_disbursement',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cash_disbursement','parent_disbursement');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211011_083213_add_cancel_parent_in_cash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}
