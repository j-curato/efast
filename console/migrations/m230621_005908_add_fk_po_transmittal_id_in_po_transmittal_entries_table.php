<?php

use yii\db\Migration;

/**
 * Class m230621_005908_add_fk_po_transmittal_id_in_po_transmittal_entries_table
 */
class m230621_005908_add_fk_po_transmittal_id_in_po_transmittal_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal_entries', 'fk_po_transmittal_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_transmittal_entries', 'fk_po_transmittal_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230621_005908_add_fk_po_transmittal_id_in_po_transmittal_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
