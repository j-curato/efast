<?php

use yii\db\Migration;

/**
 * Class m230616_072747_update_advances_entries_for_liquidation_view
 */
class m230616_072747_update_advances_entries_for_liquidation_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS advances_entries_for_liquidation ;
CREATE VIEW advances_entries_for_liquidation as  
WITH cte_gd_checks as (
    SELECT 
    cash_disbursement_items.fk_dv_aucs_id,
    books.`name` as book_name
    FROM 
    cash_disbursement
    JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
    LEFT JOIN books ON cash_disbursement.book_id = books.id
    WHERE 
    cash_disbursement_items.is_deleted = 0
    AND cash_disbursement.is_cancelled = 0
    AND NOT EXISTS (SELECT * FROM cash_disbursement c WHERE c.is_cancelled = 1 AND c.parent_disbursement = cash_disbursement.id)
    )
    SELECT
    
    advances_entries.id,
    advances.province,
    bank_account.id as bank_account_id,
    CONCAT(bank_account.account_number,'-',bank_account.account_name) as bank_account_name,
    advances_entries.fund_source,
    advances_entries.amount,
    liq.total_liquidation,
    advances_entries.amount -COALESCE(liq.total_liquidation, 0) as balance,
    dv_aucs.particular,
    cte_gd_checks.book_name
    
    FROM advances
    JOIN dv_aucs ON advances.dv_aucs_id = dv_aucs.id
    JOIN advances_entries ON advances.id = advances_entries.advances_id
    LEFT JOIN bank_account ON advances.bank_account_id = bank_account.id
    LEFT JOIN office ON bank_account.fk_office_id = office.id
     LEFT JOIN(SELECT SUM(liquidation_entries.withdrawals)as total_liquidation,
    liquidation_entries.advances_entries_id
    FROM liquidation_entries GROUP BY liquidation_entries.advances_entries_id) as liq
    ON advances_entries.id = liq.advances_entries_id
    
    LEFT JOIN cte_gd_checks ON dv_aucs.id = cte_gd_checks.fk_dv_aucs_id
    WHERE advances_entries.is_deleted NOT IN (1,9)
    
    
    ")
            ->query();
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
        echo "m230616_072747_update_advances_entries_for_liquidation_view cannot be reverted.\n";

        return false;
    }
    */
}
