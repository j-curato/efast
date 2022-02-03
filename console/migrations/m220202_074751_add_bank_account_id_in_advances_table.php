<?php

use yii\db\Migration;

/**
 * Class m220202_074751_add_bank_account_id_in_advances_table
 */
class m220202_074751_add_bank_account_id_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances', 'bank_account_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances', 'bank_account_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220202_074751_add_bank_account_id_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
