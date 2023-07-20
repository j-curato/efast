<?php

use yii\db\Migration;

/**
 * Class m230719_075243_alter_id_in_supplemental_ppmp_non_cse_items_table
 */
class m230719_075243_alter_id_in_supplemental_ppmp_non_cse_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-prItm-fk_ppmp_non_cse_item_id', 'pr_purchase_request_item');
        $this->dropIndex('idx-prItm-fk_ppmp_non_cse_item_id', 'pr_purchase_request_item');

        $this->alterColumn('pr_purchase_request_item', 'fk_ppmp_non_cse_item_id', $this->bigInteger());
        $this->alterColumn('supplemental_ppmp_non_cse_items', 'id', $this->bigInteger());

        $this->createIndex('idx-prItm-fk_ppmp_non_cse_item_id', 'pr_purchase_request_item', 'fk_ppmp_non_cse_item_id');
        $this->addForeignKey('fk-prItm-fk_ppmp_non_cse_item_id', 'pr_purchase_request_item', 'fk_ppmp_non_cse_item_id', 'supplemental_ppmp_non_cse_items', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230719_075243_alter_id_in_supplemental_ppmp_non_cse_items_table cannot be reverted.\n";

        return false;
    }
    */
}
