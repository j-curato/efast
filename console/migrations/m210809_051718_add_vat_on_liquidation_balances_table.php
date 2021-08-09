<?php

use yii\db\Migration;

/**
 * Class m210809_051718_add_vat_on_liquidation_balances_table
 */
class m210809_051718_add_vat_on_liquidation_balances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation_balances','total_vat_nonvat',$this->decimal(10,2));
        $this->addColumn('liquidation_balances','total_expanded',$this->decimal(10,2));
        $this->addColumn('liquidation_balances','total_liquidation_damage',$this->decimal(10,2));
    }

    /**
     * {@inheritdoc}Cardinality violation: 1241 Operand should contain 1 column(s)
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation_balances','total_vat_nonvat');
        $this->dropColumn('liquidation_balances','total_expanded');
        $this->dropColumn('liquidation_balances','total_liquidation_damage');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210809_051718_add_vat_on_liquidation_balances_table cannot be reverted.\n";

        return false;
    }
    */
}
