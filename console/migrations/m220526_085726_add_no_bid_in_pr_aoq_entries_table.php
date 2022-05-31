<?php

use yii\db\Migration;

/**
 * Class m220526_085726_add_no_bid_in_pr_aoq_entries_table
 */
class m220526_085726_add_no_bid_in_pr_aoq_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('pr_aoq_entries','no_bid',$this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_aoq_entries','no_bid');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220526_085726_add_no_bid_in_pr_aoq_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
