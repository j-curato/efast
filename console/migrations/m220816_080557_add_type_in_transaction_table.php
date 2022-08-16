<?php

use yii\db\Migration;

/**
 * Class m220816_080557_add_type_in_transaction_table
 */
class m220816_080557_add_type_in_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220816_080557_add_type_in_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
