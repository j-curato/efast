<?php

use yii\db\Migration;

/**
 * Class m220210_030718_update_cibr_strored_procedure
 */
class m220210_030718_update_cibr_strored_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP PROCEDURE IF EXISTS cibr_function;
        CREATE PROCEDURE cibr_function (province VARCHAR(20),r_period VARCHAR(20),bank_account_id BIGINT)
        BEGIN 
        
        SELECT
        liquidation.check_date,
        liquidation.check_number,
        CONCAT(IFNULL(liquidation.payee,po_transaction.payee),' - ',IFNULL(liquidation.particular,po_transaction.particular)) as particular,
        0 as amount,
        IFNULL(liq_entries.withdrawals,0) as withdrawals,
        chart_of_accounts.uacs as gl_object_code,
        chart_of_accounts.general_ledger as gl_account_title
        
        FROM liquidation
        RIGHT  JOIN 
        (SELECT 
        liquidation_entries.liquidation_id,
        IFNULL(liquidation_entries.new_chart_of_account_id,liquidation_entries.chart_of_account_id) as chart_id, 
        SUM(liquidation_entries.withdrawals) as withdrawals
        
        FROM liquidation_entries 
        LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
        LEFT JOIN advances ON advances_entries.advances_id=advances.id
        WHERE
        liquidation_entries.reporting_period = r_period
        AND
        advances.bank_account_id = bank_account_id
        GROUP BY liquidation_entries.id,IFNULL(liquidation_entries.new_chart_of_account_id,liquidation_entries.chart_of_account_id)
        ) as liq_entries
        
        ON
        liquidation.id=
        liq_entries.liquidation_id
        LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
        LEFT JOIN chart_of_accounts ON liq_entries.chart_id = chart_of_accounts.id
        LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
        
        WHERE
        liquidation.province = province
        
            
        UNION ALL
        
        SELECT 
        cash_disbursement.issuance_date as check_date,
        cash_disbursement.check_or_ada_no as check_number,
        CONCAT(payee.account_name,' - ',dv_aucs.particular) as particular,
        SUM(advances_entries.amount) as amount,
        0 as withdrawals,
        '' as gl_object_code,
        '' as gl_account_title
        FROM advances_entries
        LEFT JOIN advances ON advances_entries.advances_id = advances.id
        LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
        LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
        WHERE
        advances.province = province
        
        AND advances.bank_account_id = bank_account_id
        AND advances_entries.reporting_period = r_period
        AND advances_entries.is_deleted NOT IN  (1,9)
        GROUP BY advances_entries.cash_disbursement_id
        UNION ALL 
        
        SELECT 
        
        liquidation.check_date ,
        liquidation.check_number,
        liquidation.payee  as particular,
        0 as amount,
        0 as withdrawals,
            '' as gl_object_code,
        '' as gl_account_title
        
        FROM liquidation
        WHERE
        liquidation.province = province
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

        $sql = <<<SQL
        DROP PROCEDURE IF EXISTS cibr_function;
        CREATE PROCEDURE cibr_function (province VARCHAR(20),r_period VARCHAR(20))
        BEGIN 
                
        SELECT
        liquidation.check_date,
        liquidation.check_number,
        CONCAT(IFNULL(liquidation.payee,po_transaction.payee),' - ',IFNULL(liquidation.particular,po_transaction.particular)) as particular,
        0 as amount,
        IFNULL(liq_entries.withdrawals,0) as withdrawals,
        chart_of_accounts.uacs as gl_object_code,
        chart_of_accounts.general_ledger as gl_account_title

        FROM liquidation
        RIGHT  JOIN 
        (SELECT 
        liquidation_entries.liquidation_id,
        IFNULL(liquidation_entries.new_chart_of_account_id,liquidation_entries.chart_of_account_id) as chart_id, 
        SUM(liquidation_entries.withdrawals) as withdrawals

        FROM liquidation_entries 
        WHERE
        liquidation_entries.reporting_period = r_period
        GROUP BY liquidation_entries.id,IFNULL(liquidation_entries.new_chart_of_account_id,liquidation_entries.chart_of_account_id)
        ) as liq_entries

        ON
        liquidation.id=
        liq_entries.liquidation_id
        LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
        LEFT JOIN chart_of_accounts ON liq_entries.chart_id = chart_of_accounts.id
            
        WHERE
        liquidation.province = province
            
        UNION ALL

        SELECT 
        cash_disbursement.issuance_date as check_date,
        cash_disbursement.check_or_ada_no as check_number,
        CONCAT(payee.account_name,' - ',dv_aucs.particular) as particular,
        SUM(advances_entries.amount) as amount,
        0 as withdrawals,
        '' as gl_object_code,
        '' as gl_account_title
        FROM advances_entries
        LEFT JOIN advances ON advances_entries.advances_id = advances.id
        LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
        LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
        WHERE
        advances.province = province
        AND advances_entries.reporting_period = r_period
        AND advances_entries.is_deleted !=1
        GROUP BY advances_entries.cash_disbursement_id
        UNION ALL 

        SELECT 

        liquidation.check_date ,
        liquidation.check_number,
        liquidation.payee  as particular,
        0 as amount,
        0 as withdrawals,
            '' as gl_object_code,
        '' as gl_account_title

        FROM liquidation
        WHERE
        liquidation.province = province
        AND liquidation.reporting_period = r_period
        AND liquidation.payee LIKE 'cancelled%';
                    
        END

    SQL;

        $this->execute($sql);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220210_030718_update_cibr_strored_procedure cannot be reverted.\n";

        return false;
    }
    */
}
