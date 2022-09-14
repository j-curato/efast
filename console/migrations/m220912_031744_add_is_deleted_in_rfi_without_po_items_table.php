<?php

use yii\db\Migration;

/**
 * Class m220912_031744_add_is_deleted_in_rfi_without_po_items_table
 */
class m220912_031744_add_is_deleted_in_rfi_without_po_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('rfi_without_po_items', 'is_deleted', $this->boolean()->defaultValue(0));
        $this->addColumn('rfi_without_po_items', 'deleted_at', $this->timestamp()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('rfi_without_po_items', 'is_deleted');
        $this->dropColumn('rfi_without_po_items', 'deleted_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220912_031744_add_is_deleted_in_rfi_without_po_items_table cannot be reverted.\n";

        return false;
    }
    */
}
