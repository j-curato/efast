<?php

use yii\db\Migration;

/**
 * Class m211011_090149_create_cancelled_disbursements_view
 */
class m211011_090149_create_cancelled_disbursements_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
        CREATE VIEW cancelled_disbursements as 
            SELECT
            cash_disbursement.id,
            books.`name` as book_name,
            dv_aucs.dv_number,
            cash_disbursement.reporting_period,
            cash_disbursement.mode_of_payment,
            cash_disbursement.check_or_ada_no,
            cash_disbursement.issuance_date,
            cash_disbursement.ada_number,
            cash_disbursement.parent_disbursement,
            cash_disbursement.is_cancelled,
            dv_total.total as dv_amount
            FROM 
            cash_disbursement
            LEFT JOIN books ON cash_disbursement.book_id = books.id
            LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
            LEFT JOIN (SELECT SUM(dv_aucs_entries.amount_disbursed) as total, dv_aucs_entries.dv_aucs_id 
            FROM dv_aucs_entries
            GROUP BY dv_aucs_entries.dv_aucs_id
            ) as dv_total ON cash_disbursement.dv_aucs_id = dv_total.dv_aucs_id
            WHERE
            cash_disbursement.parent_disbursement IS NOT NULL
            AND cash_disbursement.is_cancelled = 1
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS cancelled_disbursements")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211011_090149_create_cancelled_disbursements_view cannot be reverted.\n";

        return false;
    }
    */
}
