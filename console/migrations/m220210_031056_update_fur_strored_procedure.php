<?php

use yii\db\Migration;

/**
 * Class m220210_031056_update_fur_strored_procedure
 */
class m220210_031056_update_fur_strored_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sqlTrigger = <<<SQL
        DROP PROCEDURE IF EXISTS fur;
        CREATE PROCEDURE fur(province VARCHAR(50),r_period VARCHAR(50),bank_account_id BIGINT)
        BEGIN
            SELECT 
            advances_entries.fund_source,
            advances_entries.report_type,
            advances_cash.amount as total_advances,
            liquidation_total.total_withdrawals,
            IFNULL(b_balance.begining_balance,0) as begining_balance,
            dv_aucs.particular
            FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id  = cash_disbursement.id
            LEFT JOIN dv_aucs  ON cash_disbursement.dv_aucs_id = dv_aucs.id
            LEFT JOIN books ON cash_disbursement.book_id = books.id
            LEFT JOIN (
                SELECT 
            liquidation_entries.advances_entries_id,
            SUM(liquidation_entries.withdrawals) as total_withdrawals
            FROM 
            liquidation_entries
            LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
                LEFT JOIN liquidation ON liquidation_entries.liquidation_id  = liquidation.id
            LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
                WHERE
                advances.bank_account_id = bank_account_id
                AND
            advances.province = province
            AND liquidation_entries.reporting_period =r_period
            GROUP BY liquidation_entries.advances_entries_id
            ) as liquidation_total ON advances_entries.id = liquidation_total.advances_entries_id
            LEFT JOIN 
            (
                SELECT 
            advances_entries.id,
            advances_entries.amount
            FROM 
            advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            WHERE
                advances.bank_account_id = bank_account_id
                AND advances.province=province
            AND advances_entries.reporting_period = r_period
            ) as advances_cash ON advances_entries.id = advances_cash.id
            LEFT JOIN (
                SELECT
                advances_entries.id,
            advances_entries.amount - IFNULL(liquidation_totals.total,0) as begining_balance
                FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            LEFT JOIN 
            (SELECT liquidation_entries.advances_entries_id,
            IFNULL(SUM(liquidation_entries.withdrawals),0) as total
            FROM liquidation_entries

                LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id  = advances_entries.id
                LEFT JOIN advances ON advances_entries.advances_id = advances.id
            WHERE
            liquidation_entries.reporting_period < r_period
                AND advances.bank_account_id = bank_account_id
            GROUP BY liquidation_entries.advances_entries_id
            )as liquidation_totals 
                ON advances_entries.id = liquidation_totals.advances_entries_id
            WHERE
                advances.province =province
                AND advances.bank_account_id = bank_account_id
            AND advances_entries.reporting_period  < r_period
            ORDER BY advances_entries.id
                ) as b_balance ON advances_entries.id = b_balance.id
            WHERE advances.province = province
                AND advances.bank_account_id = bank_account_id
            AND advances_entries.is_deleted NOT IN (1,9)
            ORDER BY cash_disbursement.issuance_date ASC;
            END
        SQL;

        $this->execute($sqlTrigger);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sqlTrigger = <<<SQL
                DROP PROCEDURE IF EXISTS fur;
                CREATE PROCEDURE fur(province VARCHAR(50),r_period VARCHAR(50))
                BEGIN 
                SELECT 
                        advances_entries.fund_source,
                advances_entries.report_type,
                advances_cash.amount as total_advances,
                        liquidation_total.total_withdrawals,
                        IFNULL(b_balance.begining_balance,0) as begining_balance,
                        dv_aucs.particular


                FROM advances_entries
                LEFT JOIN advances ON advances_entries.advances_id = advances.id
                        LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id  = cash_disbursement.id
                        LEFT JOIN dv_aucs  ON cash_disbursement.dv_aucs_id = dv_aucs.id
                        LEFT JOIN books ON cash_disbursement.book_id = books.id
                LEFT JOIN (
                SELECT 
                liquidation_entries.advances_entries_id,
                SUM(liquidation_entries.withdrawals) as total_withdrawals
                FROM 
                liquidation_entries
                LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
                LEFT JOIN advances ON advances_entries.advances_id = advances.id
                WHERE
                advances.province = province
                AND liquidation_entries.reporting_period =r_period
                GROUP BY liquidation_entries.advances_entries_id
                ) as liquidation_total ON advances_entries.id = liquidation_total.advances_entries_id
                LEFT JOIN 
                (
                SELECT 
                advances_entries.id,
                advances_entries.amount
                FROM 
                advances_entries
                LEFT JOIN advances ON advances_entries.advances_id = advances.id
                WHERE
                advances.province=province
                AND advances_entries.reporting_period = r_period

                ) as advances_cash ON advances_entries.id = advances_cash.id

                        LEFT JOIN (

                        SELECT

                        advances_entries.id,
                        advances_entries.amount - IFNULL(liquidation_totals.total,0) as begining_balance


                        FROM advances_entries
                        LEFT JOIN advances ON advances_entries.advances_id = advances.id
                        LEFT JOIN 
                        (SELECT liquidation_entries.advances_entries_id,
                        IFNULL(SUM(liquidation_entries.withdrawals),0) as total
                        FROM liquidation_entries
                        WHERE
                        liquidation_entries.reporting_period < r_period
                        GROUP BY liquidation_entries.advances_entries_id
                        )
                        as
                        liquidation_totals 

                        ON advances_entries.id = liquidation_totals.advances_entries_id
                        WHERE

                        advances.province =province
                        AND advances_entries.reporting_period  < r_period
                        ORDER BY advances_entries.id

                        ) as b_balance ON advances_entries.id = b_balance.id
                        WHERE advances.province = province
                    AND advances_entries.is_deleted !=1
                        ORDER BY cash_disbursement.issuance_date ASC
                        ;
            
                END
        SQL;

        $this->execute($sqlTrigger);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220210_031056_update_fur_strored_procedure cannot be reverted.\n";

        return false;
    }
    */
}
