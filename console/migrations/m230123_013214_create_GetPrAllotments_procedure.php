<?php

use yii\db\Migration;

/**
 * Class m230123_013214_create_GetPrAllotments_procedure
 */
class m230123_013214_create_GetPrAllotments_procedure extends Migration
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
                    pr_allotment_view.allotment_entry_id,
                    pr_purchase_request_allotments.id as pr_allotment_item_id,
                    pr_allotment_view.mfo_name,
                    pr_allotment_view.fund_source_name,
                    pr_allotment_view.account_title,
                    pr_allotment_view.amount,
                    pr_allotment_view.balance,
                    pr_purchase_request_allotments.amount as gross_amount,
                    UPPER(pr_allotment_view.office_name) as office_name,
                    UPPER(pr_allotment_view.division) as division
                    FROM pr_purchase_request_allotments
                    LEFT JOIN  pr_allotment_view ON pr_purchase_request_allotments.fk_record_allotment_entries_id = pr_allotment_view.allotment_entry_id
                    WHERE pr_purchase_request_allotments.is_deleted = 0
                    AND pr_purchase_request_allotments.fk_purchase_request_id = pr_id ;
            END //
            DELIMITER ;
            ")
            ->execute();
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
        echo "m230123_013214_create_GetPrAllotments_procedure cannot be reverted.\n";

        return false;
    }
    */
}
