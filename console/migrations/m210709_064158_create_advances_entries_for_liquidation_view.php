<?php

use yii\db\Migration;

/**
 * Class m210709_064158_create_advances_entries_for_liquidation_view
 */
class m210709_064158_create_advances_entries_for_liquidation_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("CREATE VIEW advances_entries_for_liquidation as
        SELECT 
        advances_entries.id,
        advances.province,
        advances_entries.fund_source,
        advances_entries.amount,
        liq.total_liquidation,
        advances_entries.amount -COALESCE(liq.total_liquidation, 0) as balance,
        dv_aucs.particular
        FROM advances_entries
        INNER JOIN advances ON advances_entries.advances_id =advances.id
        LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
        LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
        
        LEFT JOIN(SELECT SUM(liquidation_entries.withdrawals)as total_liquidation,
        liquidation_entries.advances_entries_id
        FROM liquidation_entries GROUP BY liquidation_entries.advances_entries_id) as liq
        ON advances_entries.id = liq.advances_entries_id 
        WHERE advances_entries.is_deleted = 0 ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW advances_entries_for_liquidation')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210709_064158_create_advances_entries_for_liquidation_view cannot be reverted.\n";

        return false;
    }
    */
}
