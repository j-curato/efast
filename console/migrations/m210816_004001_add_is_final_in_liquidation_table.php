<?php

use yii\db\Migration;

/**
 * Class m210816_004001_add_is_final_in_liquidation_table
 */
class m210816_004001_add_is_final_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','is_final',$this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','is_final');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210816_004001_add_is_final_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
