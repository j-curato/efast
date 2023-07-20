<?php

use yii\db\Migration;

/**
 * Class m230719_072851_alter_id_in_supplemental_ppmp_cse_table
 */
class m230719_072851_alter_id_in_supplemental_ppmp_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-prItm-fk_ppmp_cse_item_id', 'pr_purchase_request_item');
        $this->dropIndex('idx-prItm-fk_ppmp_cse_item_id', 'pr_purchase_request_item');
        $this->alterColumn('pr_purchase_request_item', 'fk_ppmp_cse_item_id', $this->bigInteger());
        $this->alterColumn('supplemental_ppmp_cse', 'id', $this->bigInteger());
        $this->createIndex('idx-prItm-fk_ppmp_cse_item_id', 'pr_purchase_request_item', 'fk_ppmp_cse_item_id');
        $this->addForeignKey('fk-prItm-fk_ppmp_cse_item_id', 'pr_purchase_request_item', 'fk_ppmp_cse_item_id', 'supplemental_ppmp_cse', 'id', 'RESTRICT');
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
        echo "m230719_072851_alter_id_in_supplemental_ppmp_cse_table cannot be reverted.\n";

        return false;
    }
    */
}
