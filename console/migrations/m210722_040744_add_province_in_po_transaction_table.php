<?php

use yii\db\Migration;

/**
 * Class m210722_040744_add_province_in_po_transaction_table
 */
class m210722_040744_add_province_in_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transaction', 'province', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_transaction', 'province');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210722_040744_add_province_in_po_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
