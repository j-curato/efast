<?php

use yii\db\Migration;

/**
 * Class m220210_022011_add_bank_account_id_in_cibr_table
 */
class m220210_022011_add_bank_account_id_in_cibr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cibr','bank_account_id',$this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cibr','bank_account_id');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220210_022011_add_bank_account_id_in_cibr_table cannot be reverted.\n";

        return false;
    }
    */
}
