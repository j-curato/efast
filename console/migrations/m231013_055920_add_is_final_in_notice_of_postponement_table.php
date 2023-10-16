<?php

use yii\db\Migration;

/**
 * Class m231013_055920_add_is_final_in_notice_of_postponement_table
 */
class m231013_055920_add_is_final_in_notice_of_postponement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('notice_of_postponement', 'is_final', $this->boolean()->defaultValue(0));
        $this->addColumn('notice_of_postponement', 'final_at', $this->time()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('notice_of_postponement', 'is_final');
        $this->dropColumn('notice_of_postponement', 'final_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231013_055920_add_is_final_in_notice_of_postponement_table cannot be reverted.\n";

        return false;
    }
    */
}
