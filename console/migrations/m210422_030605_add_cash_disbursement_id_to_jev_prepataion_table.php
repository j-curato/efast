<?php

use yii\db\Migration;

/**
 * Class m210422_030605_add_cash_disbursement_id_to_jev_prepataion_table
 */
class m210422_030605_add_cash_disbursement_id_to_jev_prepataion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_preparation','cash_disbursement_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_preparation','cash_disbursement_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210422_030605_add_cash_disbursement_id_to_jev_prepataion_table cannot be reverted.\n";

        return false;
    }
    */
}
