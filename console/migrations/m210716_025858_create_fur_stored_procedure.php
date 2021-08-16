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
        CREATE PROCEDURE fur(province VARCHAR(50),r_period VARCHAR(50),prev_r_period VARCHAR (50))

        BEGIN
        SELECT 
        advances_entries.fund_source,
        advances.report_type,
        ROUND(fur.total_advances,2) as total_advances,
        ROUND(fur.total_withdrawals,2) as total_withdrawals,
        ROUND(fur.balance,2) as balance,
        ROUND(prev_r_period.prev_balance,2) as prev_balance
        FROM advances_entries
        INNER JOIN advances ON advances_entries.advances_id = advances.id
        LEFT JOIN (
        SELECT 
        advances_liquidation.fund_source,
        SUM(advances_liquidation.amount) as total_advances,
        SUM(advances_liquidation.withdrawals) as total_withdrawals,
        SUM(advances_liquidation.amount) - SUM(advances_liquidation.withdrawals) as balance
        FROM advances_liquidation
        where 
        advances_liquidation.reporting_period = r_period
        AND advances_liquidation.province=province
        GROUP BY advances_liquidation.fund_source

        ) as fur ON advances_entries.fund_source = fur.fund_source

        LEFT JOIN (
        SELECT 
        advances_liquidation.fund_source,
        SUM(advances_liquidation.amount) - SUM(advances_liquidation.withdrawals) as prev_balance
        FROM advances_liquidation
        where 
        advances_liquidation.reporting_period = prev_r_period
        AND advances_liquidation.province=province
        GROUP BY advances_liquidation.fund_source) as prev_r_period on  advances_entries.fund_source = prev_r_period.fund_source

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
