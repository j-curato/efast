<?php

use yii\db\Migration;

/**
 * Class m210512_020356_add_is_cancelled_in_liquidation_table
 */
class m210512_020356_add_is_cancelled_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','is_cancelled',$this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','is_cancelled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210512_020356_add_is_cancelled_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
