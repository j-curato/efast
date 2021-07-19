<?php

use yii\db\Migration;

/**
 * Class m210716_035548_create_conso_fur_store_procedure
 */
class m210716_035548_create_conso_fur_store_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sqlTrigger =<<<SQL
        CREATE PROCEDURE conso_fur (province VARCHAR(50),r_period VARCHAR(50),prev_r_period VARCHAR(50))
        BEGIN

        SELECT 
        advances.report_type,
        ROUND(q.b_balance,2) as b_balance,
        ROUND(q.f_total_recieve,2) as f_total_recieve,
        ROUND(q.f_total_disbursements,2) as f_total_disbursements

        FROM advances
        LEFT JOIN 
        (
        SELECT
        beginning_balance.b_balance,
        SUM(advances_liquidation.amount)as f_total_recieve,
        SUM(advances_liquidation.withdrawals) as f_total_disbursements,
        advances_liquidation.report_type 

        FROM advances_liquidation 
        LEFT JOIN (
        SELECT 
        COALESCE(SUM(advances_liquidation.amount),0)-
        COALESCE(SUM(advances_liquidation.withdrawals),0)  as b_balance, 
        advances_liquidation.report_type

        FROM advances_liquidation 

        WHERE
        advances_liquidation.reporting_period =prev_r_period
        AND advances_liquidation.province =province
        GROUP BY advances_liquidation.report_type 
        ) as beginning_balance ON  advances_liquidation.report_type = beginning_balance.report_type
        WHERE
        advances_liquidation.reporting_period =r_period
        AND advances_liquidation.province =province
        GROUP BY advances_liquidation.report_type 

        ) as q ON advances.report_type= q.report_type

        WHERE
        advances.province = province
        GROUP BY advances.report_type;
        END 
        SQL;

        $this->execute($sqlTrigger);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP PROCEDURE IF EXISTS conso_fur")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210716_035548_create_conso_fur_store_procedure cannot be reverted.\n";

        return false;
    }
    */
}
