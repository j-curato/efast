<?php

use yii\db\Migration;

/**
 * Class m211021_092106_add_exclude_in_raaf_in_liquidation_table
 */
class m211021_092106_add_exclude_in_raaf_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation', 'exclude_in_raaf', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation', 'exclude_in_raaf');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211021_092106_add_exclude_in_raaf_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
