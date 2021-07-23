<?php

use yii\db\Migration;

/**
 * Class m210722_083915_add_status_in_po_transmittal_entries_table
 */
class m210722_083915_add_status_in_po_transmittal_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal_entries','status',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_transmittal_entries','status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210722_083915_add_status_in_po_transmittal_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
