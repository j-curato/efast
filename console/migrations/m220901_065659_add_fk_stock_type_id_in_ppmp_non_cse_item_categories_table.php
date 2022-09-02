<?php

use yii\db\Migration;

/**
 * Class m220901_065659_add_fk_stock_type_id_in_ppmp_non_cse_item_categories_table
 */
class m220901_065659_add_fk_stock_type_id_in_ppmp_non_cse_item_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ppmp_non_cse_item_categories', 'fk_stock_type_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ppmp_non_cse_item_categories', 'fk_stock_type_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220901_065659_add_fk_stock_type_id_in_ppmp_non_cse_item_categories_table cannot be reverted.\n";

        return false;
    }
    */
}
