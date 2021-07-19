<?php

use yii\db\Migration;

/**
 * Class m210719_005023_add_status_in_liquidation_table
 */
class m210719_005023_add_status_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','status',$this->string()->defaultValue('at_po'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210719_005023_add_status_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
