<?php

use yii\db\Migration;

/**
 * Class m230509_063207_add_constraints_in_pr_purchase_request_item_table
 */
class m230509_063207_add_constraints_in_pr_purchase_request_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-prItm-fk_ppmp_non_cse_item_id', 'pr_purchase_request_item', 'fk_ppmp_non_cse_item_id');
        $this->createIndex('idx-prItm-fk_ppmp_cse_item_id', 'pr_purchase_request_item', 'fk_ppmp_cse_item_id');
        $this->addForeignKey('fk-prItm-fk_ppmp_non_cse_item_id', 'pr_purchase_request_item', 'fk_ppmp_non_cse_item_id', 'supplemental_ppmp_non_cse_items', 'id', 'RESTRICT');
        $this->addForeignKey('fk-prItm-fk_ppmp_cse_item_id', 'pr_purchase_request_item', 'fk_ppmp_cse_item_id', 'supplemental_ppmp_cse', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-prItm-fk_ppmp_non_cse_item_id', 'pr_purchase_request_item');
        $this->dropForeignKey('fk-prItm-fk_ppmp_cse_item_id', 'pr_purchase_request_item');
        $this->dropIndex('idx-prItm-fk_ppmp_non_cse_item_id', 'pr_purchase_request_item');
        $this->dropIndex('idx-prItm-fk_ppmp_cse_item_id', 'pr_purchase_request_item');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230509_063207_add_constraints_in_pr_purchase_request_item_table cannot be reverted.\n";

        return false;
    }
    */
}
