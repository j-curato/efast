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
        $sqlTrigger = <<<SQL
        CREATE PROCEDURE fur(province VARCHAR(50),r_period VARCHAR(50))

        BEGIN
        
        SELECT 
        advances_entries.fund_source,
        advances_entries.advances_type,
        advances_cash.amount,
        liquidation_total.total_withdrawals
        FROM advances_entries
        LEFT JOIN advances ON advances_entries.advances_id = advances.id
        LEFT JOIN (

        SELECT 
        advances_entries.id,
        SUM(liquidation_entries.withdrawals) as total_withdrawals
        FROM 
        liquidation_entries
        LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
        LEFT JOIN advances ON advances_entries.advances_id = advances.id
        WHERE
        advances.province = province
        AND liquidation_entries.reporting_period =r_period
        GROUP BY liquidation_entries.advances_entries_id
        ) as liquidation_total ON advances_entries.id = liquidation_total.id
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


        WHERE advances.province = province
        AND (advances_cash.amount IS NOT NULL
        OR liquidation_total.total_withdrawals IS NOT NULL)


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
