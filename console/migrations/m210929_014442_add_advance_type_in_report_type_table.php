<?php

use yii\db\Migration;

/**
 * Class m210929_014442_add_advance_type_in_report_type_table
 */
class m210929_014442_add_advance_type_in_report_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('report_type', 'advance_type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('report_type', 'advance_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210929_014442_add_advance_type_in_report_type_table cannot be reverted.\n";

        return false;
    }
    */
}
