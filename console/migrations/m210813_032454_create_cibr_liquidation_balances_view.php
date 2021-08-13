<?php

use yii\db\Migration;

/**
 * Class m210813_032454_create_cibr_liquidation_balances_view
 */
class m210813_032454_create_cibr_liquidation_balances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        CREATE VIEW cibr_liquidation_balances as 
        SELECT
        advances.province,
        liquidation_entries.reporting_period,
        SUM(liquidation_entries.withdrawals) as total_withdrawals
        FROM 
        liquidation_entries
        LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id  = advances_entries.id
        LEFT JOIN advances ON advances_entries.advances_id = advances.id
        GROUP BY
        advances.province,
        liquidation_entries.reporting_period
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW IF EXISTS cibr_liquidation_balances')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210813_032454_create_cibr_liquidation_balances_view cannot be reverted.\n";

        return false;
    }
    */
}
