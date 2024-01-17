<?php

use yii\db\Migration;

/**
 * Class m240115_014824_add_is_bidding_attribute_in_pr_mode_of_procurement_table
 */
class m240115_014824_add_is_bidding_attribute_in_pr_mode_of_procurement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_mode_of_procurement', 'is_bidding', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_mode_of_procurement', 'is_bidding');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240115_014824_add_is_bidding_attribute_in_pr_mode_of_procurement_table cannot be reverted.\n";

        return false;
    }
    */
}
