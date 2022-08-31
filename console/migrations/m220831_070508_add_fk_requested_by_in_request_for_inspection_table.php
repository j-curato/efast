<?php

use yii\db\Migration;

/**
 * Class m220831_070508_add_fk_requested_by_in_request_for_inspection_table
 */
class m220831_070508_add_fk_requested_by_in_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request_for_inspection', 'fk_requested_by', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('request_for_inspection', 'fk_requested_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220831_070508_add_fk_requested_by_in_request_for_inspection_table cannot be reverted.\n";

        return false;
    }
    */
}
