<?php

use yii\db\Migration;

/**
 * Class m230125_031354_remove_bank_account_id_column_in_liquidation_table
 */
class m230125_031354_remove_bank_account_id_column_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('liquidation', 'bank_account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('liquidation', 'bank_account_id', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230125_031354_remove_bank_account_id_column_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
