<?php

use yii\db\Migration;

/**
 * Class m220919_005453_update_advances_entries_for_liquidation_view
 */
class m220919_005453_update_advances_entries_for_liquidation_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS advances_entries_for_liquidation; 
CREATE VIEW advances_entries_for_liquidation AS SELECT 
advances_entries.id,
advances.province,
bank_account.id as bank_account_id,
CONCAT(bank_account.account_number,'-',bank_account.account_name) as bank_account_name,
advances_entries.fund_source,
advances_entries.amount,
liq.total_liquidation,
advances_entries.amount -COALESCE(liq.total_liquidation, 0) as balance,
dv_aucs.particular,
books.`name` as book_name
FROM advances_entries
INNER JOIN advances ON advances_entries.advances_id =advances.id
LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
LEFT JOIN bank_account ON advances.bank_account_id = bank_account.id
LEFT JOIN books ON cash_disbursement.book_id  = books.id
LEFT JOIN(SELECT SUM(liquidation_entries.withdrawals)as total_liquidation,
liquidation_entries.advances_entries_id
FROM liquidation_entries GROUP BY liquidation_entries.advances_entries_id) as liq
ON advances_entries.id = liq.advances_entries_id 
WHERE advances_entries.is_deleted = 0 ")
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
        echo "m220919_005453_update_advances_entries_for_liquidation_view cannot be reverted.\n";

        return false;
    }
    */
}
