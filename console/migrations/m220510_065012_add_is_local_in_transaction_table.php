<?php

use yii\db\Migration;

/**
 * Class m220510_065012_add_is_local_in_transaction_table
 */
class m220510_065012_add_is_local_in_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'is_local', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction', 'is_local');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220510_065012_add_is_local_in_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
