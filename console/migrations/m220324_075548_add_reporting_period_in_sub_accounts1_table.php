<?php

use yii\db\Migration;

/**
 * Class m220324_075548_add_reporting_period_in_sub_accounts1_table
 */
class m220324_075548_add_reporting_period_in_sub_accounts1_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sub_accounts1', 'reporting_period', $this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sub_accounts1', 'reporting_period');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220324_075548_add_reporting_period_in_sub_accounts1_table cannot be reverted.\n";

        return false;
    }
    */
}
