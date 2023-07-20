<?php

use yii\db\Migration;

/**
 * Class m230719_082049_alter_id_in_purchase_request_item_table
 */
class m230719_082049_alter_id_in_purchase_request_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-rfq_itm-pr_purchase_request_item_id', 'pr_rfq_item');
        $this->dropIndex('idx-rfq_itm-pr_purchase_request_item_id', 'pr_rfq_item');

        $this->alterColumn('pr_rfq_item', 'pr_purchase_request_item_id', $this->bigInteger());
        $this->alterColumn('pr_purchase_request_item', 'id', $this->bigInteger());

        YIi::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->createIndex('idx-rfq_itm-pr_purchase_request_item_id', 'pr_rfq_item', 'pr_purchase_request_item_id');
        $this->addForeignKey('fk-rfq_itm-pr_purchase_request_item_id', 'pr_rfq_item', 'pr_purchase_request_item_id', 'pr_purchase_request_item', 'id', 'RESTRICT');
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
        echo "m230719_082049_alter_id_in_purchase_request_item_table cannot be reverted.\n";

        return false;
    }
    */
}
