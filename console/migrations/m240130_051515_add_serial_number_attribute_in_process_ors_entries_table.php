<?php

use yii\db\Migration;

/**
 * Class m240130_051515_add_serial_number_attribute_in_process_ors_entries_table
 */
class m240130_051515_add_serial_number_attribute_in_process_ors_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('process_ors_entries', 'serial_number', $this->string()->unique()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('process_ors_entries', 'serial_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240130_051515_add_serial_number_attribute_in_process_ors_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
