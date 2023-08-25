<?php

use yii\db\Migration;

/**
 * Class m230825_002616_update_cibr_advances_balances_view
 */
class m230825_002616_update_cibr_advances_balances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql  = <<<SQL

            DROP VIEW IF EXISTS cibr_advances_balances;
            CREATE VIEW cibr_advances_balances as 
                SELECT 
                    advances.province,
                    advances.bank_account_id,
                    advances_entries.reporting_period,
                    SUM(advances_entries.amount)  as total
                    FROM advances_entries
                    JOIN advances ON advances_entries.advances_id = advances.id
                    JOIN dv_aucs ON advances.dv_aucs_id = dv_aucs.id
                    WHERE
                    advances_entries.is_deleted NOT IN (1,9)
                    AND dv_aucs.is_cancelled != 1
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
        echo "m230825_002616_update_cibr_advances_balances_view cannot be reverted.\n";

        return false;
    }
    */
}
