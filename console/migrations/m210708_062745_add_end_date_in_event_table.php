<?php

use yii\db\Migration;

/**
 * Class m210708_062745_add_end_date_in_event_table
 */
class m210708_062745_add_end_date_in_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event','end_date',$this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event','end_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210708_062745_add_end_date_in_event_table cannot be reverted.\n";

        return false;
    }
    */
}
