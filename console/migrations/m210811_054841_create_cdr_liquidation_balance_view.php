<?php

use yii\db\Migration;

/**
 * Class m210811_054841_create_cdr_liquidation_balance_view
 */
class m210811_054841_create_cdr_liquidation_balance_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        CREATE VIEW cdr_liquidation_balance AS 
        SELECT
        advances.province,
        liquidation_entries.reporting_period,
        cash_disbursement.book_id,
        advances_entries.report_type,
        SUM(liquidation_entries.withdrawals) as total_withdrawals,
        SUM(liquidation_entries.vat_nonvat) as total_vat,
        SUM(liquidation_entries.expanded_tax) as total_expanded,
        SUM(liquidation_entries.liquidation_damage) as total_liquidation_damage
        FROM liquidation_entries

        LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
        LEFT JOIN advances ON advances_entries.advances_id = advances.id
        LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
        GROUP BY 
        advances.province,
        liquidation_entries.reporting_period,
        advances_entries.report_type 





    SQL;
    $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW IF EXISTS cdr_liquidation_balance')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210811_054841_create_cdr_liquidation_balance_view cannot be reverted.\n";

        return false;
    }
    */
}
