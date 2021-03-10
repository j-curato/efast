<?php

use yii\db\Migration;

/**
 * Class m210310_033828_add_cashflow_id_to_jev_accounting_entries_table
 */
class m210310_033828_add_cashflow_id_to_jev_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_accounting_entries', 'cashflow_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('jev_accounting_entries', 'cashflow_id', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210310_033828_add_cashflow_id_to_jev_accounting_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
