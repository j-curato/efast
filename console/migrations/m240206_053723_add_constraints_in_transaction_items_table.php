<?php

use yii\db\Migration;

/**
 * Class m240206_053723_add_constraints_in_transaction_items_table
 */
class m240206_053723_add_constraints_in_transaction_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex('idx-transaction_items-fk_transaction_id', 'transaction_items', 'fk_transaction_id');
        $this->addForeignKey('fk-transaction_items-fk_transaction_id', 'transaction_items', 'fk_transaction_id', 'transaction', 'id', 'CASCADE');


        $this->createIndex('idx-transaction_items-fk_record_allotment_entries_id', 'transaction_items', 'fk_record_allotment_entries_id');
        $this->addForeignKey('fk-transaction_items-fk_record_allotment_entries_id', 'transaction_items', 'fk_record_allotment_entries_id', 'record_allotment_entries', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-transaction_items-fk_transaction_id', 'transaction_items');
        $this->dropIndex('idx-transaction_items-fk_transaction_id', 'transaction_items');


        $this->dropForeignKey('fk-transaction_items-fk_record_allotment_entries_id', 'transaction_items');
        $this->dropIndex('idx-transaction_items-fk_record_allotment_entries_id', 'transaction_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_053723_add_constraints_in_transaction_items_table cannot be reverted.\n";

        return false;
    }
    */
}
