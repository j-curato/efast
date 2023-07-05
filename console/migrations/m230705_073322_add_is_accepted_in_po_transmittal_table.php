<?php

use yii\db\Migration;

/**
 * Class m230705_073322_add_is_accepted_in_po_transmittal_table
 */
class m230705_073322_add_is_accepted_in_po_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal', 'is_accepted', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_transmittal', 'is_accepted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230705_073322_add_is_accepted_in_po_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
