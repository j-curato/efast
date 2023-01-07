<?php

use yii\db\Migration;

/**
 * Class m221215_013659_add_fk_record_allotment_entry_id_in_transaction_table
 */
class m221215_013659_add_fk_record_allotment_entry_id_in_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'fk_record_allotment_entry_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction', 'fk_record_allotment_entry_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221215_013659_add_fk_record_allotment_entry_id_in_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
