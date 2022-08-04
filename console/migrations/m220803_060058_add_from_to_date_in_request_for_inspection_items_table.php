<?php

use yii\db\Migration;

/**
 * Class m220803_060058_add_from_to_date_in_request_for_inspection_items_table
 */
class m220803_060058_add_from_to_date_in_request_for_inspection_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request_for_inspection_items', 'from', $this->date());
        $this->addColumn('request_for_inspection_items', 'to', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('request_for_inspection_items', 'from');
        $this->dropColumn('request_for_inspection_items', 'to');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220803_060058_add_from_to_date_in_request_for_inspection_items_table cannot be reverted.\n";

        return false;
    }
    */
}
