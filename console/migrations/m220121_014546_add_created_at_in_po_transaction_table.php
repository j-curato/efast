<?php

use yii\db\Migration;

/**
 * Class m220121_014546_add_created_at_in_po_transaction_table
 */
class m220121_014546_add_created_at_in_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transaction','created_at',$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_transaction','created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220121_014546_add_created_at_in_po_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
