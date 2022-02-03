<?php

use yii\db\Migration;

/**
 * Class m220202_075953_add_bank_account_id_in_liquidation_table
 */
class m220202_075953_add_bank_account_id_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','bank_account_id',$this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','bank_account_id');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220202_075953_add_bank_account_id_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
