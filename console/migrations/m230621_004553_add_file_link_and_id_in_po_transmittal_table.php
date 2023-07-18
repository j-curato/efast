<?php

use yii\db\Migration;

/**
 * Class m230621_004553_add_file_link_and_id_in_po_transmittal_table
 */
class m230621_004553_add_file_link_and_id_in_po_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $this->addColumn('po_transmittal', 'file_link', $this->text());
        $this->addColumn('po_transmittal', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('po_transmittal', 'file_link');
        $this->dropColumn('po_transmittal', 'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230621_004553_add_file_link_and_id_in_po_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
