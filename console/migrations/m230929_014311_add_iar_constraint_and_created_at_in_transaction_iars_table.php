<?php

use yii\db\Migration;

/**
 * Class m230929_014311_add_iar_constraint_and_created_at_in_transaction_iars_table
 */
class m230929_014311_add_iar_constraint_and_created_at_in_transaction_iars_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction_iars', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->createIndex('idx-txn_iars-fk_iar_id', 'transaction_iars', 'fk_iar_id');
        $this->addForeignKey('fk-txn_iars-fk_iar_id', 'transaction_iars', 'fk_iar_id', 'iar', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-txn_iars-fk_iar_id', 'transaction_iars');
        $this->dropIndex('idx-txn_iars-fk_iar_id', 'transaction_iars');
        $this->dropColumn('transaction_iars', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_014311_add_iar_constraint_and_created_at_in_transaction_iars_table cannot be reverted.\n";

        return false;
    }
    */
}
