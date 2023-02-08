<?php

use yii\db\Migration;

/**
 * Class m230207_015629_remove_allotment_number_in_record_allotments_table
 */
class m230207_015629_remove_allotment_number_in_record_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('record_allotments', 'allotment_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('record_allotments', 'allotment_number', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230207_015629_remove_allotment_number_in_record_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
