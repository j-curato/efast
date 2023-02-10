<?php

use yii\db\Migration;

/**
 * Class m230210_014323_add_constrainst_in_transaction_table
 */
class m230210_014323_add_constrainst_in_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->createIndex('idx-tracking_number', 'transaction', 'tracking_number', true);
        $this->createIndex('idx-fk_record_allotment_entry_id', 'transaction', 'fk_record_allotment_entry_id');
        $this->createIndex('idx-fk_book_id', 'transaction', 'fk_book_id');

        $this->addForeignKey('fk-txn-fk_record_allotment_entry_id', 'transaction', 'fk_record_allotment_entry_id', 'record_allotment_entries', 'id', 'RESTRICT');
        $this->addForeignKey('fk-txn-fk_book_id', 'transaction', 'fk_book_id', 'books', 'id', 'RESTRICT');
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-txn-fk_record_allotment_entry_id', 'transaction');
        $this->dropForeignKey('fk-txn-fk_book_id', 'transaction');

        $this->dropIndex('idx-tracking_number', 'transaction',);
        $this->dropIndex('idx-fk_record_allotment_entry_id', 'transaction',);
        $this->dropIndex('idx-fk_book_id', 'transaction',);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230210_014323_add_constrainst_in_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
