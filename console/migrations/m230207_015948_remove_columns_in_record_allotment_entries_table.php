<?php

use yii\db\Migration;

/**
 * Class m230207_015948_remove_columns_in_record_allotment_entries_table
 */
class m230207_015948_remove_columns_in_record_allotment_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('record_allotment_entries', 'lvl');
        $this->dropColumn('record_allotment_entries', 'object_code');
        $this->dropColumn('record_allotment_entries', 'report_type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('record_allotment_entries', 'lvl', $this->string());
        $this->addColumn('record_allotment_entries', 'object_code', $this->string());
        $this->addColumn('record_allotment_entries', 'report_type', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230207_015948_remove_columns_in_record_allotment_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
