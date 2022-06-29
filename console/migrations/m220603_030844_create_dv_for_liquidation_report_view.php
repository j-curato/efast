<?php

use yii\db\Migration;

/**
 * Class m220603_030844_create_dv_for_liquidation_report_view
 */
class m220603_030844_create_dv_for_liquidation_report_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS dv_for_liquidation_report;
            CREATE VIEW dv_for_liquidation_report as  
            SELECT 
                cash_disbursement.id as cash_id,
                dv_aucs.id as dv_id,
                dv_aucs.dv_number,
                        payee.account_name as payee,
                        cash_disbursement.check_or_ada_no as check_number,
                        cash_disbursement.ada_number,
                        CONCAT(dv_aucs.dv_number,'-',dv_aucs.particular) as particular,
                        cash_disbursement.issuance_date,
                        total_dv_amount.total_disbursed,
                    IFNULL(liquidation_report_items_total.total_items_amount,0) + IFNULL(liquidation_report_refunds_total.total_refund,0) as liquidated_amount,

                    total_dv_amount.total_disbursed - (	IFNULL(liquidation_report_items_total.total_items_amount,0) + IFNULL(liquidation_report_refunds_total.total_refund,0)) as balance
                        FROM cash_disbursement
                        LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
                        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
                        LEFT JOIN (SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,
                        dv_aucs_entries.dv_aucs_id 
                        FROM dv_aucs_entries GROUP BY 
                        dv_aucs_entries.dv_aucs_id
                        ) as total_dv_amount ON dv_aucs.id = total_dv_amount.dv_aucs_id
                                LEFT JOIN (SELECT 
                                ro_liquidation_report_items.fk_cash_disbursement_id,
                                SUM(ro_liquidation_report_items.amount) as total_items_amount
                                FROM ro_liquidation_report_items
                WHERE ro_liquidation_report_items.is_deleted !=1
                                GROUP BY ro_liquidation_report_items.fk_cash_disbursement_id)
                as liquidation_report_items_total ON cash_disbursement.id = liquidation_report_items_total.fk_cash_disbursement_id
                        LEFT JOIN (
                    SELECT ro_liquidation_report_refunds.fk_cash_disbursement_id,
                SUM(ro_liquidation_report_refunds.amount) as total_refund
                FROm ro_liquidation_report_refunds
                WHERE ro_liquidation_report_refunds.is_deleted !=1
                GROUP BY ro_liquidation_report_refunds.fk_cash_disbursement_id
                        ) as liquidation_report_refunds_total ON cash_disbursement.id = liquidation_report_refunds_total.fk_cash_disbursement_id

                LEFT JOIN nature_of_transaction ON dv_aucs.nature_of_transaction_id = nature_of_transaction.id
                WHERE
                        cash_disbursement.is_cancelled =0
                        AND (dv_aucs.reporting_period LIKE'2022%'  OR dv_aucs.id IN (7351,7426,7748,7869,7885,7965,8018))
                    AND nature_of_transaction.`name` = 'CA to Employees' 
            
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
        echo "m220603_030844_create_dv_for_liquidation_report_view cannot be reverted.\n";

        return false;
    }
    */
}
