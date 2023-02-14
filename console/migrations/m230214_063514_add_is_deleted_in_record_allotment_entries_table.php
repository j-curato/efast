<?php

use yii\db\Migration;

/**
 * Class m230214_063514_add_is_deleted_in_record_allotment_entries_table
 */
class m230214_063514_add_is_deleted_in_record_allotment_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotment_entries', 'is_deleted', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('record_allotment_entries', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230214_063514_add_is_deleted_in_record_allotment_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
