<?php

use yii\db\Migration;

/**
 * Class m231201_015430_add_dv_number_in_tbl_mg_liquidation_items_table
 */
class m231201_015430_add_dv_number_in_tbl_mg_liquidation_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tbl_mg_liquidation_items', 'dv_number', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tbl_mg_liquidation_items', 'dv_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231201_015430_add_dv_number_in_tbl_mg_liquidation_items_table cannot be reverted.\n";

        return false;
    }
    */
}
