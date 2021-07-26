<?php

use yii\db\Migration;

/**
 * Class m210726_024451_create_po_transmittals_pending_view
 */
class m210726_024451_create_po_transmittals_pending_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("CREATE VIEW po_transmittals_pending as 
        SELECT 
        po_transmittal.*,
        totals.total_withdrawals
        FROM po_transmittal
        LEFT JOIN (
        SELECT 
        SUM(liquidation_entries.withdrawals) as total_withdrawals,
        po_transmittal_entries.po_transmittal_number
        FROM 
        po_transmittal_entries
        LEFT JOIN liquidation ON po_transmittal_entries.liquidation_id = liquidation.id
        LEFT JOIN liquidation_entries ON liquidation.id = liquidation_entries.liquidation_id
        GROUP BY po_transmittal_entries.po_transmittal_number
        ) as totals ON po_transmittal.transmittal_number = totals.po_transmittal_number
        WHERE po_transmittal.`status` ='pending_at_ro'
        ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW po_transmittals_pending')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210726_024451_create_po_transmittals_pending_view cannot be reverted.\n";

        return false;
    }
    */
}
