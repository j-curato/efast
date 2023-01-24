<?php

use yii\db\Migration;

/**
 * Class m230123_010213_create_GetTransactionAllotmentItems_procedure
 */
class m230123_010213_create_GetTransactionAllotmentItems_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS GetTransactionAllotmentItems;
            CREATE PROCEDURE GetTransactionAllotmentItems(IN transaction_id BIGINT)
            BEGIN 
                SELECT 
                transaction_items.id as item_id,
                record_allotments_view.entry_id,
                record_allotments_view.reporting_period,
                record_allotments_view.mfo_name,
                record_allotments_view.mfo_code,
                record_allotments_view.fund_source,
                record_allotments_view.general_ledger,
                record_allotments_view.amount as allotment_amt,
                record_allotments_view.balance,
                record_allotments_view.book,
                record_allotments_view.office_name,
                record_allotments_view.division,
                transaction_items.amount
                FROM `transaction_items` 
                LEFT JOIN record_allotments_view ON  transaction_items.fk_record_allotment_entries_id = record_allotments_view.entry_id
                where transaction_items.fk_transaction_id= transaction_id
                AND transaction_items.is_deleted = 0 ;
            END 
        SQL;
        $this->execute($sql);
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
        echo "m230123_010213_create_GetTransactionAllotmentItems_procedure cannot be reverted.\n";

        return false;
    }
    */
}
