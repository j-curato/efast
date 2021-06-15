<?php

use Prophecy\Promise\ThrowPromise;
use yii\db\Migration;

/**
 * Class m210614_035411_add_check_range_id_in_liquidation_table
 */
class m210614_035411_add_check_range_id_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','check_range_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','check_range_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210614_035411_add_check_range_id_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
