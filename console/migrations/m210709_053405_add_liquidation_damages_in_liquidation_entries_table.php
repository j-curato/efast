<?php

use yii\db\Migration;

/**
 * Class m210709_053405_add_liquidation_damages_in_liquidation_entries_table
 */
class m210709_053405_add_liquidation_damages_in_liquidation_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation_entries','liquidation_damage',$this->decimal(10,2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation_entries','liquidation_damage');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210709_053405_add_liquidation_damages_in_liquidation_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
