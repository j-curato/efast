<?php

use yii\db\Migration;

/**
 * Class m220314_003906_add_fk_bac_composition_id_and_bac_date_in_pr_purchase_order_table
 */
class m220314_003906_add_fk_bac_composition_id_and_bac_date_in_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_order', 'fk_bac_composition_id', $this->integer());
        $this->addColumn('pr_purchase_order', 'bac_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_order', 'fk_bac_composition_id');
        $this->dropColumn('pr_purchase_order', 'bac_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220314_003906_add_fk_bac_composition_id_and_bac_date_in_pr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
