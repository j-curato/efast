<?php

use yii\db\Migration;

/**
 * Class m210917_034225_add_division_in_fund_source_type_table
 */
class m210917_034225_add_division_in_fund_source_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('fund_source_type','division',$this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('fund_source_type','division');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210917_034225_add_division_in_fund_source_type_table cannot be reverted.\n";

        return false;
    }
    */
}
