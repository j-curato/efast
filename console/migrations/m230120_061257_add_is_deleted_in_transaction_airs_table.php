<?php

use yii\db\Migration;

/**
 * Class m230120_061257_add_is_deleted_in_transaction_airs_table
 */
class m230120_061257_add_is_deleted_in_transaction_airs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction_iars', 'is_deleted', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction_iars', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230120_061257_add_is_deleted_in_transaction_airs_table cannot be reverted.\n";

        return false;
    }
    */
}
