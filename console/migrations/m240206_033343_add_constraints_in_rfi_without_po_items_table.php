<?php

use yii\db\Migration;

/**
 * Class m240206_033343_add_constraints_in_rfi_without_po_items_table
 */
class m240206_033343_add_constraints_in_rfi_without_po_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex('idx-rfi_without_po_items-fk_stock_id', 'rfi_without_po_items', 'fk_stock_id');
        $this->addForeignKey('fk-rfi_without_po_items-fk_stock_id', 'rfi_without_po_items', 'fk_stock_id', 'pr_stock', 'id', 'RESTRICT');

        $this->createIndex('idx-rfi_without_po_items-fk_unit_of_measure_id', 'rfi_without_po_items', 'fk_unit_of_measure_id');
        $this->addForeignKey('fk-rfi_without_po_items-fk_unit_of_measure_id', 'rfi_without_po_items', 'fk_unit_of_measure_id', 'unit_of_measure', 'id', 'RESTRICT');


        $this->createIndex('idx-rfi_without_po_items-fk_request_for_inspection_id', 'rfi_without_po_items', 'fk_request_for_inspection_id');
        $this->addForeignKey('fk-rfi_without_po_items-fk_request_for_inspection_id', 'rfi_without_po_items', 'fk_request_for_inspection_id', 'request_for_inspection', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rfi_without_po_items-fk_stock_id', 'rfi_without_po_items');
        $this->dropIndex('idx-rfi_without_po_items-fk_stock_id', 'rfi_without_po_items');

        $this->dropForeignKey('fk-rfi_without_po_items-fk_unit_of_measure_id', 'rfi_without_po_items');
        $this->dropIndex('idx-rfi_without_po_items-fk_unit_of_measure_id', 'rfi_without_po_items');


        $this->dropForeignKey('fk-rfi_without_po_items-fk_request_for_inspection_id', 'rfi_without_po_items');
        $this->dropIndex('idx-rfi_without_po_items-fk_request_for_inspection_id', 'rfi_without_po_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_033343_add_constraints_in_rfi_without_po_items_table cannot be reverted.\n";

        return false;
    }
    */
}
