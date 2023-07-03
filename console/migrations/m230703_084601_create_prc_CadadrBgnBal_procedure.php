<?php

use yii\db\Migration;

/**
 * Class m230703_084601_create_prc_CadadrBgnBal_procedure
 */
class m230703_084601_create_prc_CadadrBgnBal_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS prc_CadadrBgnBal;
            CREATE PROCEDURE   prc_CadadrBgnBal(to_prd VARCHAR(20),book_id INT)
            BEGIN
                 SELECT
                COALESCE((SELECT
                SUM(cash_received.amount )as amt
                FROM
                cash_received 
                LEFT JOIN books ON cash_received.book_id = books.id 
                WHERE 
                cash_received.reporting_period >= '2023-01'
                AND cash_received.reporting_period < to_prd
                AND books.id = book_id),0)
                -
                COALESCE((SELECT 
                SUM(
                (CASE
                WHEN 
                    cash_disbursement.is_cancelled = 1
                THEN  
                        (CASE
                            WHEN  books.type = 'mds regular'
                            THEN 
                                (CASE 
                                        WHEN 	 
                                            QUARTER(CONCAT(cash_disbursement.reporting_period,'-01')) = QUARTER(CONCAT(parent_cash.reporting_period,'-01')) 
                                            AND SUBSTRING_INDEX(cash_disbursement.reporting_period,'-',1) = SUBSTRING_INDEX(parent_cash.reporting_period,'-',1) 
                                        THEN  dv_aucs_index.ttlAmtDisbursed *-1
                                        ELSE 0
                                    END)
                            WHEN books.type = 'mds trust'
                            THEN 
                                    (CASE 
                                        WHEN SUBSTRING_INDEX(cash_disbursement.reporting_period,'-',1) = SUBSTRING_INDEX(parent_cash.reporting_period,'-',1)
                                        THEN dv_aucs_index.ttlAmtDisbursed *-1
                                        ELSE 0
                                    END)
                            ELSE dv_aucs_index.ttlAmtDisbursed *-1
                        END)
                ELSE dv_aucs_index.ttlAmtDisbursed
                END) )as amtDisbursedTtl

                FROM cash_disbursement
                JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
                JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
                LEFT JOIN books ON cash_disbursement.book_id = books.id
                LEFT JOIN cash_disbursement as parent_cash ON cash_disbursement.parent_disbursement = parent_cash.id
                WHERE 
                cash_disbursement_items.is_deleted = 0
                AND cash_disbursement.reporting_period >= '2023-01'
                AND cash_disbursement.reporting_period < to_prd
                AND books.id = book_id),0)  as begin_bal;
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
        echo "m230703_084601_create_prc_CadadrBgnBal_procedure cannot be reverted.\n";

        return false;
    }
    */
}
