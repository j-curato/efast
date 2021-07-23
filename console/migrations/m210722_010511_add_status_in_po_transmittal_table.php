<?php

use yii\db\Migration;

/**
 * Class m210722_010511_add_status_in_po_transmittal_table
 */
class m210722_010511_add_status_in_po_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal','status',$this->string()->defaultValue('pending_at_ro'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_transmittal','status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210722_010511_add_status_in_po_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
