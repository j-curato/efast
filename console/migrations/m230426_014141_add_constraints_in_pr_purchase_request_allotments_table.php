<?php

use yii\db\Migration;

/**
 * Class m230426_014141_add_constraints_in_pr_purchase_request_allotments_table
 */
class m230426_014141_add_constraints_in_pr_purchase_request_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $this->createIndex('idx-prAlt-fk_record_allotment_entries_id', 'pr_purchase_request_allotments', 'fk_record_allotment_entries_id');
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();
        $this->addForeignKey('fk-prAlt-fk_record_allotment_entries_id', 'pr_purchase_request_allotments', 'fk_record_allotment_entries_id', 'record_allotment_entries', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-prAlt-fk_record_allotment_entries_id', 'pr_purchase_request_allotments');
        $this->dropIndex('idx-prAlt-fk_record_allotment_entries_id', 'pr_purchase_request_allotments');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230426_014141_add_constraints_in_pr_purchase_request_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
