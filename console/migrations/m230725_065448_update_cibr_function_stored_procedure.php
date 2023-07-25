<?php

use yii\db\Migration;

/**
 * Class m230725_065448_update_cibr_function_stored_procedure
 */
class m230725_065448_update_cibr_function_stored_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP PROCEDURE IF EXISTS cibr_function;
        CREATE PROCEDURE cibr_function (province VARCHAR(20),r_period VARCHAR(20))
        BEGIN 
            WITH cte_gd_checks as (
                SELECT 
                cash_disbursement_items.fk_dv_aucs_id,
                cash_disbursement.check_or_ada_no as check_number,
                cash_disbursement.ada_number,
                cash_disbursement.issuance_date as check_date,
                mode_of_payments.`name` as mode_of_payment,
                books.`name` as book_name
                FROM 
                cash_disbursement
                JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
                LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
                LEFT JOIN books ON cash_disbursement.book_id = books.id
                WHERE 
                cash_disbursement_items.is_deleted = 0
                AND cash_disbursement.is_cancelled = 0
                AND NOT EXISTS (SELECT * FROM cash_disbursement c WHERE c.is_cancelled = 1 AND c.parent_disbursement = cash_disbursement.id)
            )
            SELECT
                liquidation.check_date,
                liquidation.check_number,
                CONCAT(IFNULL(liquidation.payee,po_transaction.payee),' - ',IFNULL(liquidation.particular,po_transaction.particular)) as particular,
                0 as amount,
                IFNULL(liq_entries.withdrawals,0) as withdrawals,
                IFNULL(liq_entries.vat_nonvat,0) as vat_nonvat,
                IFNULL(liq_entries.expanded_tax,0) as expanded_tax,
                IFNULL(liq_entries.liquidation_damage,0) as liquidation_damage,
                accounting_codes.object_code as gl_object_code,
                accounting_codes.account_title as gl_account_title
            FROM liquidation
            RIGHT  JOIN 
                (SELECT 
                liquidation_entries.liquidation_id,
                (CASE  
                WHEN liquidation_entries.new_object_code IS  NOT NULL THEN liquidation_entries.new_object_code
                WHEN  liquidation_entries.new_chart_of_account_id IS  NOT NULL THEN  new_chart.uacs
                ELSE orig_chart.uacs
                END) as object_code,
                SUM(liquidation_entries.withdrawals) as withdrawals,
                SUM(liquidation_entries.vat_nonvat) as 		vat_nonvat,
                SUM(liquidation_entries.expanded_tax) as 	expanded_tax,
                SUM(liquidation_entries.liquidation_damage) as liquidation_damage
            FROM liquidation_entries 
            LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
            LEFT JOIN advances ON advances_entries.advances_id=advances.id
            LEFT JOIN chart_of_accounts as orig_chart ON liquidation_entries.chart_of_account_id = orig_chart.id
            LEFT JOIN chart_of_accounts as new_chart ON liquidation_entries.chart_of_account_id = new_chart.id
            WHERE
            liquidation_entries.reporting_period = r_period
            AND
            advances.bank_account_id = bank_account_id
            GROUP BY liquidation_entries.id,IFNULL(liquidation_entries.new_chart_of_account_id,liquidation_entries.chart_of_account_id)
            ) as liq_entries ON liquidation.id=liq_entries.liquidation_id
            LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
            LEFT JOIN accounting_codes ON liq_entries.object_code = accounting_codes.object_code
            LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
            
            WHERE
            check_range.bank_account_id = bank_account_id
        
            
        UNION ALL
        
        SELECT 
        cte_gd_checks.check_date ,
        cte_gd_checks.check_number ,
        CONCAT(payee.account_name,' - ',dv_aucs.particular) as particular,
        SUM(advances_entries.amount) as amount,
        0 as withdrawals,
        0 as vat_nonvat,
        0 as expanded_tax,
        0 as liquidation_damage,
        '' as gl_object_code,
        '' as gl_account_title
        FROM advances_entries
        LEFT JOIN advances ON advances_entries.advances_id = advances.id
        LEFT JOIN dv_aucs ON advances.dv_aucs_id = dv_aucs.id
        LEFT JOIN cte_gd_checks ON dv_aucs.id = cte_gd_checks.fk_dv_aucs_id
        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
        WHERE
            advances.bank_account_id = bank_account_id  
                AND advances.bank_account_id = bank_account_id
                AND advances_entries.reporting_period = r_period
        AND advances_entries.is_deleted NOT IN  (1,9)
        AND dv_aucs.is_cancelled = 0
        GROUP BY advances.id
       UNION ALL 

        SELECT 
            liquidation.check_date ,
            liquidation.check_number,
            liquidation.payee  as particular,
            0 as amount,
            0 as withdrawals,
            0 as vat_nonvat,
            0 as expanded_tax,
            0 as liquidation_damage,
            '' as gl_object_code,
            '' as gl_account_title
        FROM liquidation
        LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
        WHERE
        check_range.bank_account_id = bank_account_id
        AND liquidation.reporting_period = r_period
        AND liquidation.payee LIKE 'cancelled%';
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
        echo "m230725_065448_update_cibr_function_stored_procedure cannot be reverted.\n";

        return false;
    }
    */
}
