<?php

use yii\db\Migration;

/**
 * Class m230405_004515_add_constraints_in_pr_purchase_request_item_table
 */
class m230405_004515_add_constraints_in_pr_purchase_request_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {



        $this->createIndex('idx-pr_itm-pr_stock_id', 'pr_purchase_request_item', 'pr_stock_id');
        $this->createIndex('idx-pr_itm-unit_of_measure_id', 'pr_purchase_request_item', 'unit_of_measure_id');

        $this->addForeignKey('fk-pr_itm-pr_stock_id', 'pr_purchase_request_item', 'pr_stock_id', 'pr_stock', 'id', 'RESTRICT');
        $this->addForeignKey('fk-pr_itm-unit_of_measure_id', 'pr_purchase_request_item', 'unit_of_measure_id', 'unit_of_measure', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-pr_itm-pr_stock_id', 'pr_purchase_request_item');
        $this->dropForeignKey('fk-pr_itm-unit_of_measure_id', 'pr_purchase_request_item');


        $this->dropIndex('idx-pr_itm-pr_stock_id', 'pr_purchase_request_item');
        $this->dropIndex('idx-pr_itm-unit_of_measure_id', 'pr_purchase_request_item');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230405_004515_add_constraints_in_pr_purchase_request_item_table cannot be reverted.\n";

        return false;
    }
    */
}
