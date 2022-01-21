<?php

use yii\db\Migration;

/**
 * Class m220121_025347_add_reporting_period_in_po_transaction_table
 */
class m220121_025347_add_reporting_period_in_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transaction','reporting_period',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('po_transaction','reporting_period');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220121_025347_add_reporting_period_in_po_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
