<?php

use yii\db\Migration;

/**
 * Class m230705_052648_add_is_returned_in_po_transmittal_entries_table
 */
class m230705_052648_add_is_returned_in_po_transmittal_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal_entries', 'is_returned', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_transmittal_entries', 'is_returned');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230705_052648_add_is_returned_in_po_transmittal_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
