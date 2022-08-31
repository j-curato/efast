<?php

use yii\db\Migration;

/**
 * Class m220831_071155_add_fk_end_user_in_inspection_report_table
 */
class m220831_071155_add_fk_end_user_in_inspection_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('inspection_report', 'fk_end_user', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->drpColumn('inspection_report', 'fk_end_user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220831_071155_add_fk_end_user_in_inspection_report_table cannot be reverted.\n";

        return false;
    }
    */
}
