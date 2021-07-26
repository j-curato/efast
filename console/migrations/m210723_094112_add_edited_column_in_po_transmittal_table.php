<?php

use yii\db\Migration;

/**
 * Class m210723_094112_add_edited_column_in_po_transmittal_table
 */
class m210723_094112_add_edited_column_in_po_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal','edited',$this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_transmittal','edited');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_094112_add_edited_column_in_po_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
