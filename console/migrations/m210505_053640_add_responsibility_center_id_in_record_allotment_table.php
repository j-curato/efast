<?php

use yii\db\Migration;

/**
 * Class m210505_053640_add_responsibility_center_id_in_record_allotment_table
 */
class m210505_053640_add_responsibility_center_id_in_record_allotment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotments','responsibility_center_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('record_allotments','responsibility_center_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210505_053640_add_responsibility_center_id_in_record_allotment_table cannot be reverted.\n";

        return false;
    }
    */
}
