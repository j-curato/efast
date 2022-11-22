<?php

use yii\db\Migration;

/**
 * Class m221122_065019_add_to_date_in_trip_ticket_table
 */
class m221122_065019_add_to_date_in_trip_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('trip_ticket', 'to_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('trip_ticket', 'to_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221122_065019_add_to_date_in_trip_ticket_table cannot be reverted.\n";

        return false;
    }
    */
}
