<?php

use yii\db\Migration;

/**
 * Class m210406_090351_add_funding_code_to_record_allotments_table
 */
class m210406_090351_add_funding_code_to_record_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotments','funding_code',$this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    $this->dropColumn('record_allotments','funding_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210406_090351_add_funding_code_to_record_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
