<?php

use yii\db\Migration;

/**
 * Class m230216_063715_add_constraints_in_process_ors_table
 */
class m230216_063715_add_constraints_in_process_ors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // serial_number
        // document_recieve_id
        // mfo_pap_code_id
        // fund_source_id
        // book_id
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->createIndex('idx-serial_number', 'process_ors', 'serial_number', true);
        $this->createIndex('idx-document_recieve_id', 'process_ors', 'document_recieve_id');
        $this->createIndex('idx-mfo_pap_code_id', 'process_ors', 'mfo_pap_code_id');
        $this->createIndex('idx-fund_source_id', 'process_ors', 'fund_source_id');
        $this->createIndex('idx-book_id', 'process_ors', 'book_id');

        $this->addForeignKey('fk-ors-document_recieve_id', 'process_ors', 'document_recieve_id', 'document_recieve', 'id', 'RESTRICT');
        $this->addForeignKey('fk-ors-mfo_pap_code_id', 'process_ors', 'mfo_pap_code_id', 'mfo_pap_code', 'id', 'RESTRICT');
        $this->addForeignKey('fk-ors-fund_source_id', 'process_ors', 'fund_source_id', 'fund_source', 'id', 'RESTRICT');
        $this->addForeignKey('fk-ors-book_id', 'process_ors', 'book_id', 'books', 'id', 'RESTRICT');
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        $this->dropForeignKey('fk-ors-document_recieve_id', 'process_ors');
        $this->dropForeignKey('fk-ors-mfo_pap_code_id', 'process_ors');
        $this->dropForeignKey('fk-ors-fund_source_id', 'process_ors');
        $this->dropForeignKey('fk-ors-book_id', 'process_ors');

        $this->dropIndex('idx-serial_number', 'process_ors');
        $this->dropIndex('idx-document_recieve_id', 'process_ors');
        $this->dropIndex('idx-mfo_pap_code_id', 'process_ors');
        $this->dropIndex('idx-fund_source_id', 'process_ors');
        $this->dropIndex('idx-book_id', 'process_ors');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230216_063715_add_constraints_in_process_ors_table cannot be reverted.\n";

        return false;
    }
    */
}
