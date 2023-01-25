<?php

use yii\db\Migration;

/**
 * Class m230123_005114_create_GetTransactionPrItems_procedure
 */
class m230123_005114_create_GetTransactionPrItems_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("

            DROP PROCEDURE IF EXISTS GetTransactionPrItems;
            DELIMITER //
            CREATE PROCEDURE GetTransactionPrItems(IN transaction_id BIGINT)
            BEGIN 
            SELECT 
                        transaction_pr_items.id,
                        purchase_request_index.id as pr_id,
                        purchase_request_index.pr_number,
                        purchase_request_index.office_name,
                        purchase_request_index.division,
                        purchase_request_index.division_program_unit,
                        purchase_request_index.purpose,
                        transaction_pr_items.amount
                        FROM transaction_pr_items
                        LEFT JOIN purchase_request_index ON transaction_pr_items.fk_pr_purchase_request_id = purchase_request_index.id
                        WHERE 
                        transaction_pr_items.fk_transaction_id = transaction_id
                        AND transaction_pr_items.is_deleted = 0;
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
        echo "m230123_005114_create_GetTransactionPrItems_procedure cannot be reverted.\n";

        return false;
    }
    */
}
