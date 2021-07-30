<?php

use yii\db\Migration;

/**
 * Class m210730_081644_add_begin_and_out_time_incash_disbursement_table
 */
class m210730_081644_add_begin_and_out_time_incash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_disbursement','begin_time',$this->time());
        $this->addColumn('cash_disbursement','out_time',$this->time());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cash_disbursement','begin_time');
        $this->dropColumn('cash_disbursement','out_time');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210730_081644_add_begin_and_out_time_incash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}
