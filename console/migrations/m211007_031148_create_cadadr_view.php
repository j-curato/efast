<?php

use yii\db\Migration;

/**
 * Class m211007_031148_create_cadadr_view
 */
class m211007_031148_create_cadadr_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        CREATE VIEW cadadr as 
         SELECT
            cash_disbursement.mode_of_payment,
            dv_aucs.reporting_period,
            dv_aucs.dv_number,
            dv_aucs.created_at as dv_date,
            cash_disbursement.check_or_ada_no,
            cash_disbursement.ada_number,
            cash_disbursement.issuance_date,
            payee.account_name,
            dv_aucs.particular,
            books.`name` book_name,
            IF(cash_disbursement.mode_of_payment LIKE'%check%' ,IFNULL(dv_aucs_entries.amount_disbursed,0),0) as check_issued,
            IF(cash_disbursement.mode_of_payment LIKE'%ada%' ,IFNULL(dv_aucs_entries.amount_disbursed,0),0) as ada_issued
            FROM cash_disbursement
            LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
            LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
            LEFT JOIN payee ON dv_aucs.payee_id = payee.id
            LEFT JOIN books ON dv_aucs.book_id = books.id
            ORDER BY dv_aucs.dv_number
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS cadadr")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211007_031148_create_cadadr_view cannot be reverted.\n";

        return false;
    }
    */
}
