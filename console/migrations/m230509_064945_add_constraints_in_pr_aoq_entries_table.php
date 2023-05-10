<?php

use yii\db\Migration;

/**
 * Class m230509_064945_add_constraints_in_pr_aoq_entries_table
 */
class m230509_064945_add_constraints_in_pr_aoq_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        $this->createIndex('idx-aoqItm-pr_aoq_id', 'pr_aoq_entries', 'pr_aoq_id');
        $this->createIndex('idx-aoqItm-payee_id', 'pr_aoq_entries', 'payee_id');
        $this->createIndex('idx-aoqItm-pr_rfq_item_id', 'pr_aoq_entries', 'pr_rfq_item_id');
        $this->addForeignKey('fk-aoqItm-payee_id', 'pr_aoq_entries', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->addForeignKey('fk-aoqItm-pr_rfq_item_id', 'pr_aoq_entries', 'pr_rfq_item_id', 'pr_rfq_item', 'id', 'RESTRICT');
        // $this->addForeignKey('fk-aoqItm-pr_aoq_id', 'pr_aoq_entries', 'pr_aoq_id', 'pr_aoq', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->dropForeignKey('fk-aoqItm-pr_aoq_id', 'pr_aoq_entries');
        $this->dropForeignKey('fk-aoqItm-payee_id', 'pr_aoq_entries');
        $this->dropForeignKey('fk-aoqItm-pr_rfq_item_id', 'pr_aoq_entries');
        $this->dropIndex('idx-aoqItm-pr_aoq_id', 'pr_aoq_entries');
        $this->dropIndex('idx-aoqItm-payee_id', 'pr_aoq_entries');
        $this->dropIndex('idx-aoqItm-pr_rfq_item_id', 'pr_aoq_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230509_064945_add_constraints_in_pr_aoq_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
