<?php

use yii\db\Migration;

/**
 * Class m210709_082218_create_liquidation_view
 */
class m210709_082218_create_liquidation_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("CREATE VIEW liquidation_view as SELECT 
        liquidation.id,
        liquidation.check_date,
        liquidation.check_number,
        liquidation.dv_number,
        liquidation.reporting_period,
        po_transaction.payee,
        po_transaction.particular,
        total_liq.total_withdrawal,
        total_liq.total_expanded,
        total_liq.total_liquidation_damage,
        total_liq.total_vat,
        po_transaction.amount as gross_payment,
        prov.province
        
        FROM liquidation
        LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
        LEFT JOIN (
        SELECT SUM(liquidation_entries.withdrawals) as total_withdrawal,
        SUM(liquidation_entries.expanded_tax) as total_expanded,
        SUM(liquidation_entries.vat_nonvat) as total_vat,
        SUM(liquidation_entries.liquidation_damage) as total_liquidation_damage,
        liquidation_entries.liquidation_id
        
        FROM liquidation_entries
        GROUP BY liquidation_entries.liquidation_id
        ) as total_liq ON liquidation.id= total_liq.liquidation_id
        LEFT JOIN (SELECT DISTINCT
        advances.province,
        liquidation.id
        FROM advances
        INNER JOIN advances_entries ON advances.id = advances_entries.advances_id
        INNER JOIN liquidation_entries ON advances_entries.id = liquidation_entries.advances_entries_id
        INNER JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
        
        
        ORDER BY liquidation.id) as prov ON liquidation.id = prov.id
        ORDER BY liquidation.check_date DESC
        ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW liquidation_view')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210709_082218_create_liquidation_view cannot be reverted.\n";

        return false;
    }
    */
}
