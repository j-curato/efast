<?php

use yii\db\Migration;

/**
 * Class m210818_130957_add_is_realign_in_liquidation_entries_table
 */
class m210818_130957_add_is_realign_in_liquidation_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation_entries','is_realign',$this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation_entries','is_realign');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210818_130957_add_is_realign_in_liquidation_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
