<?php

use yii\db\Migration;

/**
 * Class m220905_045732_remove_fk_fund_source_id_in_ppmp_non_cse_items_table
 */
class m220905_045732_remove_fk_fund_source_id_in_ppmp_non_cse_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('ppmp_non_cse_items', 'fk_fund_source_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('ppmp_non_cse_items', 'fk_fund_source_id', $this->bigInteger());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220905_045732_remove_fk_fund_source_id_in_ppmp_non_cse_items_table cannot be reverted.\n";

        return false;
    }
    */
}
