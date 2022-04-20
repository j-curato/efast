<?php

use yii\db\Migration;

/**
 * Class m220419_035415_add_bank_account_id_on_liquidation_reporting_period_table
 */
class m220419_035415_add_bank_account_id_on_liquidation_reporting_period_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation_reporting_period', 'bank_account_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation_reporting_period', 'bank_account_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220419_035415_add_bank_account_id_on_liquidation_reporting_period_table cannot be reverted.\n";

        return false;
    }
    */
}
