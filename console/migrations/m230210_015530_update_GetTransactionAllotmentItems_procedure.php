<?php

use yii\db\Migration;

/**
 * Class m230210_015530_update_GetTransactionAllotmentItems_procedure
 */
class m230210_015530_update_GetTransactionAllotmentItems_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Yii::$app->db->createCommand("DROP PROCEDURE IF EXISTS GetTransactionAllotmentItems;
        //     DELIMITER //
        //     CREATE PROCEDURE GetTransactionAllotmentItems(IN transaction_id BIGINT)
        //     BEGIN 
        //         SELECT 
        //         transaction_items.id as item_id,
        //         record_allotment_detailed.allotment_entry_id,
        //         record_allotment_detailed.reporting_period,
        //         record_allotment_detailed.mfo_name,
        //         record_allotment_detailed.mfo_code,
        //         record_allotment_detailed.fund_source_name,
        //         record_allotment_detailed.account_title,
        //         record_allotment_detailed.amount as allotment_amt,
        //         record_allotment_detailed.balance,
        //         record_allotment_detailed.book,
        //         record_allotment_detailed.office_name,
        //         record_allotment_detailed.division,
        //         record_allotment_detailed.amount,
        //                 record_allotment_detailed.allotmentNumber,
        //                 record_allotment_detailed.budget_year
        //         FROM `transaction_items` 
        //         LEFT JOIN record_allotment_detailed ON  transaction_items.fk_record_allotment_entries_id = record_allotment_detailed.allotment_entry_id
        //         where  transaction_items.fk_transaction_id= transaction_id
        //         AND transaction_items.is_deleted = 0 ;
        //     END //
        //     DELIMITER ;
        //     ")->query();
        $sql = <<<SQL
                DROP PROCEDURE IF EXISTS GetTransactionAllotmentItems;
                CREATE PROCEDURE GetTransactionAllotmentItems(IN transaction_id BIGINT)
                BEGIN 
                    SELECT 
                    transaction_items.id as item_id,
                    record_allotment_detailed.allotment_entry_id,
                    record_allotment_detailed.reporting_period,
                    record_allotment_detailed.mfo_name,
                    record_allotment_detailed.mfo_code,
                    record_allotment_detailed.fund_source_name,
                    record_allotment_detailed.account_title,
                    record_allotment_detailed.amount as allotment_amt,
                    record_allotment_detailed.balance,
                    record_allotment_detailed.book,
                    record_allotment_detailed.office_name,
                    record_allotment_detailed.division,
                    transaction_items.amount,
                            record_allotment_detailed.allotmentNumber,
                            record_allotment_detailed.budget_year
                    FROM `transaction_items` 
                    LEFT JOIN record_allotment_detailed ON  transaction_items.fk_record_allotment_entries_id = record_allotment_detailed.allotment_entry_id
                    where  transaction_items.fk_transaction_id= transaction_id
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
        echo "m230210_015530_update_GetTransactionAllotmentItems_procedure cannot be reverted.\n";

        return false;
    }
    */
}
