<?php

use yii\db\Migration;

/**
 * Class m210212_091113_add_is_active_to_chart_of_accounts_table
 */
class m210212_091113_add_is_active_to_chart_of_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chart_of_accounts','is_active',$this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('chart_of_accounts','is_active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210212_091113_add_is_active_to_chart_of_accounts_table cannot be reverted.\n";

        return false;
    }
    */
}
