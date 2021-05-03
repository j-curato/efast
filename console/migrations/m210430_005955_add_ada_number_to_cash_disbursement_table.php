<?php

use yii\db\Migration;

/**
 * Class m210430_005955_add_ada_number_to_cash_disbursement_table
 */
class m210430_005955_add_ada_number_to_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_disbursement','ada_number',$this->string(40));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cash_disbursement','ada_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210430_005955_add_ada_number_to_cash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}
