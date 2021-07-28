<?php

use yii\db\Migration;

/**
 * Class m210728_025235_add_report_type_in_advances_entries_table
 */
class m210728_025235_add_report_type_in_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances_entries','report_type',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances_entries','report_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210728_025235_add_report_type_in_advances_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
