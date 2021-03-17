<?php

use yii\db\Migration;

/**
 * Class m210316_062746_add_from_and_to_in_fund_category_and_classification_code_table
 */
class m210316_062746_add_from_and_to_in_fund_category_and_classification_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('fund_category_and_classification_code','from',$this->integer());
        $this->addColumn('fund_category_and_classification_code','to',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('fund_category_and_classification_code','from');
        $this->dropColumn('fund_category_and_classification_code','to');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210316_062746_add_from_and_to_in_fund_category_and_classification_code_table cannot be reverted.\n";

        return false;
    }
    */
}
