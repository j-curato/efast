<?php

use yii\db\Migration;

/**
 * Class m231013_011137_add_type_in_notice_of_postponement_table
 */
class m231013_011137_add_type_in_notice_of_postponement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('notice_of_postponement', 'type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('notice_of_postponement', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231013_011137_add_type_in_notice_of_postponement_table cannot be reverted.\n";

        return false;
    }
    */
}
