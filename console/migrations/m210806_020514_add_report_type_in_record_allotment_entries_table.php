<?php

use yii\db\Migration;

/**
 * Class m210806_020514_add_report_type_in_record_allotment_entries_table
 */
class m210806_020514_add_report_type_in_record_allotment_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotment_entries','report_type',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('record_allotment_entries','report_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210806_020514_add_report_type_in_record_allotment_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
