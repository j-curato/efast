<?php

use yii\db\Migration;

/**
 * Class m230206_012314_update_GetPrAllotments_procedure
 */
class m230206_012314_update_GetPrAllotments_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("

        DROP PROCEDURE IF EXISTS GetPrAllotments;
        DELIMITER //
        CREATE PROCEDURE GetPrAllotments(IN pr_id BIGINT)
        BEGIN 
            SELECT 
            record_allotment_detailed.allotment_entry_id,
            pr_purchase_request_allotments.id as pr_allotment_item_id,
            record_allotment_detailed.mfo_name,
            record_allotment_detailed.fund_source_name,
            record_allotment_detailed.account_title,
            record_allotment_detailed.amount,
            record_allotment_detailed.balance,
            pr_purchase_request_allotments.amount as gross_amount,
            UPPER(record_allotment_detailed.office_name) as office_name,
            UPPER(record_allotment_detailed.division) as division,
            record_allotment_detailed.budget_year,
            record_allotment_detailed.allotmentNumber,
            record_allotment_detailed.balance
            FROM pr_purchase_request_allotments
            LEFT JOIN  record_allotment_detailed ON pr_purchase_request_allotments.fk_record_allotment_entries_id = record_allotment_detailed.allotment_entry_id
            WHERE pr_purchase_request_allotments.is_deleted = 0
            AND pr_purchase_request_allotments.fk_purchase_request_id = pr_id ;
        END //
        DELIMITER ;
        ")
            ->query();
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
        echo "m230206_012314_update_GetPrAllotments_procedure cannot be reverted.\n";

        return false;
    }
    */
}
