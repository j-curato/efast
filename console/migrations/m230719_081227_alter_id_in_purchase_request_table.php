<?php

use yii\db\Migration;

/**
 * Class m230719_081227_alter_id_in_purchase_request_table
 */
class m230719_081227_alter_id_in_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-pr_purchase_request_item-pr_purchase_request_id', 'pr_purchase_request_item');
        $this->dropIndex('idx-pr_purchase_request_item-pr_purchase_request_id', 'pr_purchase_request_item');

        $this->dropForeignKey('fk-fk_purchase_request_id', 'pr_purchase_request_allotments');
        $this->dropIndex('idx-fk_purchase_request_id', 'pr_purchase_request_allotments');



        $this->alterColumn('pr_purchase_request_item', 'pr_purchase_request_id', $this->bigInteger());
        $this->alterColumn('pr_purchase_request_allotments', 'fk_purchase_request_id', $this->bigInteger());
        $this->alterColumn('pr_purchase_request', 'id', $this->bigInteger());


        $this->createIndex('idx-pr_purchase_request_item-pr_purchase_request_id', 'pr_purchase_request_item', 'pr_purchase_request_id');
        $this->addForeignKey('fk-pr_purchase_request_item-pr_purchase_request_id', 'pr_purchase_request_item', 'pr_purchase_request_id', 'pr_purchase_request', 'id', 'CASCADE');

        $this->createIndex('idx-fk_purchase_request_id', 'pr_purchase_request_allotments', 'fk_purchase_request_id');
        $this->addForeignKey('fk-fk_purchase_request_id', 'pr_purchase_request_allotments', 'fk_purchase_request_id', 'pr_purchase_request', 'id', 'CASCADE');
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
        echo "m230719_081227_alter_id_in_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
