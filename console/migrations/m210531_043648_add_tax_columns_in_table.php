<?php

use yii\db\Migration;

/**
 * Class m210531_043648_add_tax_columns_in_table
 */
class m210531_043648_add_tax_columns_in_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','chart_of_account_id',$this->integer());
        $this->addColumn('liquidation','advances_entries_id',$this->integer());
        $this->addColumn('liquidation','withdrawals',$this->decimal(15,2));
        $this->addColumn('liquidation','vat_nonvat',$this->decimal(15,2));
        $this->addColumn('liquidation','ewt_goods_services',$this->decimal(15,2));


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210531_043648_add_tax_columns_in_table cannot be reverted.\n";

        return false;
    }
    */
}
