<?php

use yii\db\Migration;

/**
 * Class m210713_084107_create_liquidation_entries_view
 */
class m210713_084107_create_liquidation_entries_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("CREATE VIEW  liquidation_entries_view as
        SELECT 
        liquidation.dv_number,
        liquidation.reporting_period,
        liquidation.check_date,
        liquidation.check_number,
        advances_entries.fund_source,
        po_transaction.particular,
        po_transaction.payee,
        chart_of_accounts.uacs as object_code,
        chart_of_accounts.general_ledger as account_title,
        liquidation_entries.withdrawals,
        liquidation_entries.vat_nonvat,
        liquidation_entries.expanded_tax,
        liquidation_entries.liquidation_damage,
        COALESCE(liquidation_entries.withdrawals)
        + COALESCE(liquidation_entries.vat_nonvat)
        +COALESCE(liquidation_entries.expanded_tax) as gross_payment,
        advances.province
        
        
        FROM liquidation
        LEFT JOIN liquidation_entries ON liquidation.id =liquidation_entries.liquidation_id
        LEFT JOIN advances_entries on liquidation_entries.advances_entries_id = advances_entries.id
        LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
        LEFT JOIN chart_of_accounts ON liquidation_entries.chart_of_account_id =chart_of_accounts.id
        LEFT JOIN advances ON advances_entries.advances_id = advances.id
        
        
        ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW liquidation_entries_view")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210713_084107_create_liquidation_entries_view cannot be reverted.\n";

        return false;
    }
    */
}
