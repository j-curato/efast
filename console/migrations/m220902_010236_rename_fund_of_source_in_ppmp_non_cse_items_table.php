<?php

use yii\db\Migration;

/**
 * Class m220902_010236_rename_fund_of_source_in_ppmp_non_cse_items_table
 */
class m220902_010236_rename_fund_of_source_in_ppmp_non_cse_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('ppmp_non_cse_items','fk_fund_of_source_id','fk_fund_source_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('ppmp_non_cse_items','fk_fund_source_id','fk_fund_of_source_id');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220902_010236_rename_fund_of_source_in_ppmp_non_cse_items_table cannot be reverted.\n";

        return false;
    }
    */
}
