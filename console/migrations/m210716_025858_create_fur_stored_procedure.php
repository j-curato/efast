<?php

use yii\db\Migration;

/**
 * Class m210716_025858_create_fur_stored_procedure
 */
class m210716_025858_create_fur_stored_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sqlTrigger =<<<SQL
        CREATE PROCEDURE fur(province VARCHAR(50),r_period VARCHAR(50))

            BEGIN 
            SELECT 
            advances_entries.fund_source,
            advances_entries.advances_type,
            advances_cash.amount as total_advances,
            liquidation_total.total_withdrawals,
            IFNULL(b_balance.begining_balance,0) as begining_balance,
            dv_aucs.particular

            FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id  = cash_disbursement.id
            LEFT JOIN dv_aucs  ON cash_disbursement.dv_aucs_id = dv_aucs.id
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
            WHERE advances.province = province;
            END
        SQL;
        $this->execute($sqlTrigger);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP PROCEDURE IF EXISTS fur ")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210716_025858_create_fur_stored_procedure cannot be reverted.\n";

        return false;
    }
    */
}
