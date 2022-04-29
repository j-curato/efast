<?php

use yii\db\Migration;

/**
 * Class m220407_060015_update_cadadr_balances_view
 */
class m220407_060015_update_cadadr_balances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS cadadr_balances;
            CREATE VIEW cadadr_balances as 
            SELECT
                cadadr.book_name,
                reporting_period,
                SUM(nca_recieve) as total_nca_recieve,
                SUM(check_issued) as total_check_issued,
                SUM(ada_issued) as total_ada_issued
                FROM cadadr
                WHERE cadadr.is_cancelled !=1
                GROUP BY
                cadadr.book_name,
                reporting_period 

                UNION ALL 

                SELECT
                q.book_name,
                q.reporting_period,
                SUM(q.nca_recieve) as total_nca_recieve,
                SUM(q.check_issued) as total_check_issued,
                SUM(q.ada_issued) as total_ada_issued
                FROM 
                (
                SELECT
                
                cadadr.reporting_period,
                cadadr.book_name,
                0 as nca_recieve,
                (CASE
                WHEN QUARTER(CONCAT(cadadr.reporting_period,'-01')) = QUARTER(CONCAT(cadadr.cancelled_r_period,'-01')) AND SUBSTRING_INDEX(cadadr.reporting_period,'-',1) = SUBSTRING_INDEX(cadadr.cancelled_r_period,'-',1)
                THEN cadadr.check_issued 
                ELSE
                cadadr.check_issued * (-1)
                END
                ) as check_issued,
                (CASE
                WHEN QUARTER(CONCAT(cadadr.reporting_period,'-01')) = QUARTER(CONCAT(cadadr.cancelled_r_period,'-01')) AND SUBSTRING_INDEX(cadadr.reporting_period,'-',1) = SUBSTRING_INDEX(cadadr.cancelled_r_period,'-',1)
                THEN cadadr.ada_issued 
                ELSE
                cadadr.ada_issued * (-1)
                END
                ) as ada_issued
                FROM cadadr
                WHERE 
                is_cancelled =1
                ORDER BY issuance_date
                )as q
                GROUP BY
                q.book_name,
                q.reporting_period 
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220407_060015_update_cadadr_balances_view cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220407_060015_update_cadadr_balances_view cannot be reverted.\n";

        return false;
    }
    */
}
