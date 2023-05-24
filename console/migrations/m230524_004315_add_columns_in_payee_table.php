<?php

use yii\db\Migration;

/**
 * Class m230524_004315_add_columns_in_payee_table
 */
class m230524_004315_add_columns_in_payee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payee', 'fk_bank_id', $this->integer());
        $this->addColumn('payee', 'account_num', $this->string());
        $this->createIndex('idx-pye-fk_bank_id', 'payee', 'fk_bank_id');
        $this->addForeignKey('fk-pye-fk_bank_id', 'payee', 'fk_bank_id', 'banks', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-pye-fk_bank_id', 'payee');
        $this->dropIndex('idx-pye-fk_bank_id', 'payee');
        $this->dropColumn('payee', 'fk_bank_id');
        $this->dropColumn('payee', 'account_num');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230524_004315_add_columns_in_payee_table cannot be reverted.\n";

        return false;
    }
    */
}
