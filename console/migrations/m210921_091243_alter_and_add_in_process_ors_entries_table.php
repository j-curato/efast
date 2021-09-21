<?php

use yii\db\Migration;

/**
 * Class m210921_091243_alter_and_add_in_process_ors_entries_table
 */
class m210921_091243_alter_and_add_in_process_ors_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('process_ors_entries', 'amount', $this->decimal(10, 2));
        $this->addColumn('process_ors_entries', 'reporting_period', $this->string(20));
        $this->addColumn('process_ors_entries', 'record_allotment_entries_id', $this->integer());
        $this->addColumn('process_ors_entries', 'is_realign', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('process_ors_entries', 'amount', $this->decimal(10, 2));
        $this->dropColumn('process_ors_entries', 'reporting_period');
        $this->dropColumn('process_ors_entries', 'record_allotment_entries_id');
        $this->dropColumn('process_ors_entries', 'is_realign');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210921_091243_alter_and_add_in_process_ors_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
