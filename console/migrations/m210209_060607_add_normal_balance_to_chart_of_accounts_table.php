<?php

use yii\db\Migration;

/**
 * Class m210209_060607_add_normal_balance_to_chart_of_accounts_table
 */
class m210209_060607_add_normal_balance_to_chart_of_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chart_of_accounts','normal_balance',$this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('chart_of_accounts', 'normal_balance');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210209_060607_add_normal_balance_to_chart_of_accounts_table cannot be reverted.\n";

        return false;
    }
    */
}
