<?php

use yii\db\Migration;

/**
 * Class m220419_011847_update_cibr_advances_balances_view
 */
class m220419_011847_update_cibr_advances_balances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql=<<<SQL
             DROP VIEW IF EXISTS cibr_advances_balances;
           CREATE VIEW cibr_advances_balances as 
            SELECT 
                advances.province,
                            advances.bank_account_id,
                advances_entries.reporting_period,
                SUM(advances_entries.amount)  as total
                FROM advances_entries
                LEFT JOIN advances ON advances_entries.advances_id = advances.id
                WHERE
                advances_entries.is_deleted NOT IN (1,9)
                GROUP BY advances.province,
                            advances.bank_account_id
                ,advances_entries.reporting_period
                ORDER BY advances.province 
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220419_011847_update_cibr_advances_balances_view cannot be reverted.\n";

        return false;
    }
    */
}
