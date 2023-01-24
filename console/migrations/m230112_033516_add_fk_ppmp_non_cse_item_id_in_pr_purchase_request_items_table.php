<?php

use yii\db\Migration;

/**
 * Class m230112_033516_add_fk_ppmp_non_cse_item_id_in_pr_purchase_request_items_table
 */
class m230112_033516_add_fk_ppmp_non_cse_item_id_in_pr_purchase_request_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request_item', 'fk_ppmp_non_cse_item_id', $this->integer());
        $this->addColumn('pr_purchase_request_item', 'fk_ppmp_cse_item_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_request_item', 'fk_ppmp_non_cse_item_id');
        $this->dropColumn('pr_purchase_request_item', 'fk_ppmp_cse_item_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230112_033516_add_fk_ppmp_non_cse_item_id_in_pr_purchase_request_items_table cannot be reverted.\n";

        return false;
    }
    */
}
