<?php

use yii\db\Migration;

/**
 * Class m221202_100235_update_cadadr_view
 */
class m221202_100235_update_cadadr_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS cadadr;
        CREATE VIEW cadadr as
        SELECT
        cash_disbursement.mode_of_payment,
        cash_disbursement.reporting_period,
        dv_aucs.dv_number,
        IFNULL(dv_aucs.created_at,'') as dv_date,
        IFNULL(cash_disbursement.check_or_ada_no,'')as check_or_ada_no,
        IFNULL(cash_disbursement.ada_number,'') as ada_number,
        cash_disbursement.issuance_date,
        payee.account_name,
        dv_aucs.particular,                     
        books.`name` book_name,
        0 as nca_recieve,
        (CASE
        WHEN cash_disbursement.mode_of_payment LIKE'%check%' THEN 
        IF(cash_disbursement.is_cancelled=1 ,0-IFNULL(total_dv.total_amount,0),IFNULL(total_dv.total_amount,0))
        ELSE 0
        END)         as check_issued, 
        (CASE
        WHEN cash_disbursement.mode_of_payment LIKE'%ada%' THEN 
        IF(cash_disbursement.is_cancelled=1 ,0-IFNULL(total_dv.total_amount,0),IFNULL(total_dv.total_amount,0))
        ELSE 0
        END)as ada_issued,
        cash_disbursement.is_cancelled,
        IF(cash_disbursement.is_cancelled=1,DATE_FORMAT(cash_disbursement.issuance_date,'%Y-%m'),NULL) as cancelled_r_period
                FROM cash_disbursement
                LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
                LEFT JOIN payee ON dv_aucs.payee_id = payee.id
                LEFT JOIN books ON cash_disbursement.book_id = books.id
            LEFT JOIN (SELECT dv_aucs_entries.dv_aucs_id,SUM(dv_aucs_entries.amount_disbursed) as total_amount
            FROM dv_aucs_entries
WHERE 
dv_aucs_entries.is_deleted !=1
GROUP BY dv_aucs_entries.dv_aucs_id) as total_dv ON dv_aucs.id = total_dv.dv_aucs_id

            UNION ALL
            SELECT
                '' as mode_of_payment,
                cash_recieved.reporting_period,
                ''dv_number,
                '' as dv_date,
            '' as check_or_ada_no,
                '' as ada_number,
                cash_recieved.date as issuance_date, 


            (
            CASE 
                WHEN cash_recieved.nca_no IS NOT NULL OR  cash_recieved.nca_no !='' THEN cash_recieved.nca_no
                WHEN cash_recieved.nft_no IS NOT NULL OR  cash_recieved.nft_no !='' THEN cash_recieved.nft_no
                        WHEN cash_recieved.nta_no IS NOT NULL OR  cash_recieved.nta_no !='' THEN cash_recieved.nta_no
            END) as account_name,
        cash_recieved.purpose as particular,
            books.`name` as  book_name,
        cash_recieved.amount nca_recieve,
        0 as check_issued,
            0 as ada_issued,
        0 as is_cancelled,
        null as cancelled_r_period
        FROM
        cash_recieved 
        LEFT JOIN books ON cash_recieved.book_id = books.id 
        ")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221202_100235_update_cadadr_view cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221202_100235_update_cadadr_view cannot be reverted.\n";

        return false;
    }
    */
}