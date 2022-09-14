<?php

use yii\db\Migration;

/**
 * Class m220909_003035_add_transaction_type_in_request_for_inspection_table
 */
class m220909_003035_add_transaction_type_in_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request_for_inspection', 'transaction_type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('request_for_inspection', 'transaction_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220909_003035_add_transaction_type_in_request_for_inspection_table cannot be reverted.\n";

        return false;
    }
    */
}
