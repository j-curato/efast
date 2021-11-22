<?php

use yii\db\Migration;

/**
 * Class m211122_015807_create_dv_cadadr_view
 */
class m211122_015807_create_dv_cadadr_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $sql = <<<SQL
            DROP VIEW IF EXISTS dv_cadadr;
            CREATE VIEW dv_cadadr as 
            SELECT
            cash_disbursement.mode_of_payment,

            IFNULL(cash_disbursement.reporting_period,dv_aucs.reporting_period) as reporting_period,
            dv_aucs.dv_number,
            IFNULL(dv_aucs.created_at,'') as dv_date,
            IFNULL(cash_disbursement.check_or_ada_no,'')as check_or_ada_no,
            IFNULL(cash_disbursement.ada_number,'') as ada_number,
                IFNULL(cash_disbursement.issuance_date,dv_aucs.created_at)as issuance_date,
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
            END)         as ada_issued,
            total_dv.total_amount as dv_amount
            FROM cash_disbursement
            RIGHT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
            LEFT JOIN payee ON dv_aucs.payee_id = payee.id
            LEFT JOIN books ON dv_aucs.book_id = books.id
            LEFT JOIN (SELECT dv_aucs_entries.dv_aucs_id,SUM(dv_aucs_entries.amount_disbursed) as total_amount
            FROM dv_aucs_entries GROUP BY dv_aucs_entries.dv_aucs_id) as total_dv ON dv_aucs.id = total_dv.dv_aucs_id
            WHERE dv_aucs.is_cancelled = 0
            UNION ALL
            SELECT
                '' as mode_of_payment,
                cash_recieved.reporting_period,
                ''dv_number,
                '' as dv_date,
            '' as check_or_ada_no,
                '' as ada_number,
                cash_recieved.date as q, 
                            '' as issuance_date,

            (
            CASE 
                WHEN cash_recieved.nca_no IS NOT NULL OR  cash_recieved.nca_no !='' THEN cash_recieved.nca_no
                WHEN cash_recieved.nft_no IS NOT NULL OR  cash_recieved.nft_no !='' THEN cash_recieved.nft_no
                        WHEN cash_recieved.nta_no IS NOT NULL OR  cash_recieved.nta_no !='' THEN cash_recieved.nta_no
            END) as particular,
            books.`name` as  book_name,
            cash_recieved.amount nca_recieve,
            0 as check_issued,
                0 as ada_issued,
            0 as dv_amount

            FROM
            cash_recieved 
            LEFT JOIN books ON cash_recieved.book_id = books.id 
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS dv_cadadr ")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211122_015807_create_dv_cadadr_view cannot be reverted.\n";

        return false;
    }
    */
}
