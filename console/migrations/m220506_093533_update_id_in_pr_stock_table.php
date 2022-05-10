<?php

use yii\db\Migration;

/**
 * Class m220506_093533_update_id_in_pr_stock_table
 */
class m220506_093533_update_id_in_pr_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('pr_stock', 'id', $this->bigInteger());
        $this->alterColumn('pr_purchase_request_item', 'pr_stock_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('pr_stock', 'id', $this->bigInteger());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220506_093533_update_id_in_pr_stock_table cannot be reverted.\n";

        return false;
    }
    */
}
