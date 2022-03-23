<?php

use yii\db\Migration;

/**
 * Class m220323_024243_add_bank_account_id_in_cdr_table
 */
class m220323_024243_add_bank_account_id_in_cdr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cdr', 'fk_bank_account_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cdr', 'fk_bank_account_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220323_024243_add_bank_account_id_in_cdr_table cannot be reverted.\n";

        return false;
    }
    */
}
