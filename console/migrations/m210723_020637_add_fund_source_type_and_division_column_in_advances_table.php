<?php

use yii\db\Migration;

/**
 * Class m210723_020637_add_fund_source_type_and_division_column_in_advances_table
 */
class m210723_020637_add_fund_source_type_and_division_column_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances_entries','fund_source_type',$this->string());
        $this->addColumn('advances_entries','division',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances_entries','fund_source_type');
        $this->dropColumn('advances_entries','division');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_020637_add_fund_source_type_and_division_column_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
