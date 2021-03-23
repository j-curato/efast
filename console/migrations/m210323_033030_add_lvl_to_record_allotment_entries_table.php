<?php

use yii\db\Migration;

/**
 * Class m210323_033030_add_lvl_to_record_allotment_entries_table
 */
class m210323_033030_add_lvl_to_record_allotment_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotment_entries','lvl',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('record_allotment_entries','lvl');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210323_033030_add_lvl_to_record_allotment_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
