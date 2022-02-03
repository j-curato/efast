<?php

use yii\db\Migration;

/**
 * Class m220202_080108_add_bank_account_id_in_check_range_table
 */
class m220202_080108_add_bank_account_id_in_check_range_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('check_range','bank_account_id',$this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('check_range','bank_account_id');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220202_080108_add_bank_account_id_in_check_range_table cannot be reverted.\n";

        return false;
    }
    */
}
