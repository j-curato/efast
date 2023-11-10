<?php

use yii\db\Migration;

/**
 * Class m231110_055321_remove_to_date_in_notice_of_postponement_items_table
 */
class m231110_055321_remove_to_date_in_notice_of_postponement_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('notice_of_postponement_items', 'to_date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('notice_of_postponement_items', 'to_date', $this->date());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231110_055321_remove_to_date_in_notice_of_postponement_items_table cannot be reverted.\n";

        return false;
    }
    */
}
