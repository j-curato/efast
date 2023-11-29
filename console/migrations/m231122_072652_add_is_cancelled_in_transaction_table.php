<?php

use yii\db\Migration;

/**
 * Class m231122_072652_add_is_cancelled_in_transaction_table
 */
class m231122_072652_add_is_cancelled_in_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'is_cancelled', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction', 'is_cancelled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231122_072652_add_is_cancelled_in_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
