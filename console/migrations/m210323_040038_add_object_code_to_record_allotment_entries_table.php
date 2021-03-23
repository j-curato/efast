<?php

use yii\db\Migration;

/**
 * Class m210323_040038_add_object_code_to_record_allotment_entries_table
 */
class m210323_040038_add_object_code_to_record_allotment_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotment_entries','object_code',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('record_allotment_entries','object_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210323_040038_add_object_code_to_record_allotment_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
