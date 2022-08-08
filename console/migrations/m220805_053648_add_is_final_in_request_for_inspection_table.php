<?php

use yii\db\Migration;

/**
 * Class m220805_053648_add_is_final_in_request_for_inspection_table
 */
class m220805_053648_add_is_final_in_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request_for_inspection', 'is_final', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('request_for_inspection', 'is_final');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220805_053648_add_is_final_in_request_for_inspection_table cannot be reverted.\n";

        return false;
    }
    */
}
