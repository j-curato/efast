<?php

use yii\db\Migration;

/**
 * Class m230720_004622_alter_id_in_pr_rfq_item_table
 */
class m230720_004622_alter_id_in_pr_rfq_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-aoqItm-pr_rfq_item_id', 'pr_aoq_entries');
        $this->dropIndex('idx-aoqItm-pr_rfq_item_id', 'pr_aoq_entries');

        $this->alterColumn('pr_aoq_entries', 'pr_rfq_item_id', $this->bigInteger());
        $this->alterColumn('pr_rfq_item', 'id', $this->bigInteger());

        // YIi::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->createIndex('idx-aoqItm-pr_rfq_item_id', 'pr_aoq_entries', 'pr_rfq_item_id');
        $this->addForeignKey('fk-aoqItm-pr_rfq_item_id', 'pr_aoq_entries', 'pr_rfq_item_id', 'pr_rfq_item', 'id', 'RESTRICT');
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
        echo "m230720_004622_alter_id_in_pr_rfq_item_table cannot be reverted.\n";

        return false;
    }
    */
}
