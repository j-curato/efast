<?php

use yii\db\Migration;

/**
 * Class m210709_015936_add_po_responsibility_center_id_in_po_transaction_table
 */
class m210709_015936_add_po_responsibility_center_id_in_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transaction','po_responsibility_center_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_transaction','po_responsibility_center_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210709_015936_add_po_responsibility_center_id_in_po_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
