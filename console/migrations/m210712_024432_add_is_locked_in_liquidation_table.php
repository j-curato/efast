<?php

use yii\db\Migration;

/**
 * Class m210712_024432_add_is_locked_in_liquidation_table
 */
class m210712_024432_add_is_locked_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation', 'is_locked', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation', 'is_locked');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210712_024432_add_is_locked_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
