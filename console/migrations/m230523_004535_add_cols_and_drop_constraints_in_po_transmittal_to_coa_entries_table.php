<?php

use yii\db\Migration;

/**
 * Class m230523_004535_add_cols_and_drop_constraints_in_po_transmittal_to_coa_entries_table
 */
class m230523_004535_add_cols_and_drop_constraints_in_po_transmittal_to_coa_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-po_transmittal_to_coa_entries-po_transmittal_number', 'po_transmittal_to_coa_entries');
        $this->dropIndex('idx-po_transmittal_to_coa_entries-po_transmittal_number', 'po_transmittal_to_coa_entries');


        $this->dropForeignKey('fk-po_transmittal_to_coa_entries-po_transmittal_to_coa_number', 'po_transmittal_to_coa_entries');
        $this->dropIndex('idx-po_transmittal_to_coa_entries-po_transmittal_to_coa_number', 'po_transmittal_to_coa_entries');

        $this->addColumn('po_transmittal_to_coa_entries', 'fk_po_transmittal_id', $this->bigInteger());
        $this->addColumn('po_transmittal_to_coa_entries', 'fk_po_transmittal_to_coa_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->execute();
        $this->createIndex('idx-po_transmittal_to_coa_entries-po_transmittal_number', 'po_transmittal_to_coa_entries', 'po_transmittal_number');
        $this->addForeignKey('fk-po_transmittal_to_coa_entries-po_transmittal_number', 'po_transmittal_to_coa_entries', 'po_transmittal_number', 'po_transmittal', 'transmittal_number', 'RESTRICT');


        $this->createIndex('idx-po_transmittal_to_coa_entries-po_transmittal_to_coa_number', 'po_transmittal_to_coa_entries', 'po_transmittal_to_coa_number');
        $this->addForeignKey('fk-po_transmittal_to_coa_entries-po_transmittal_to_coa_number', 'po_transmittal_to_coa_entries', 'po_transmittal_to_coa_number', 'po_transmittal_to_coa', 'transmittal_number', 'RESTRICT');

        $this->dropColumn('po_transmittal_to_coa_entries', 'fk_po_transmittal_id');
        $this->dropColumn('po_transmittal_to_coa_entries', 'fk_po_transmittal_to_coa_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230523_004535_add_cols_and_drop_constraints_in_po_transmittal_to_coa_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
