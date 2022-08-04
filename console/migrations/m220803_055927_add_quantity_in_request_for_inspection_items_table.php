<?php

use yii\db\Migration;

/**
 * Class m220803_055927_add_quantity_in_request_for_inspection_items_table
 */
class m220803_055927_add_quantity_in_request_for_inspection_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request_for_inspection_items', 'quantity', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('request_for_inspection_items', 'quantity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220803_055927_add_quantity_in_request_for_inspection_items_table cannot be reverted.\n";

        return false;
    }
    */
}
