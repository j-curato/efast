<?php

use yii\db\Migration;

/**
 * Class m210811_054106_create_cdr_advances_balance_view
 */
class m210811_054106_create_cdr_advances_balance_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        CREATE VIEW cdr_advances_balance as 
            SELECT 
            advances.province,
            advances_entries.reporting_period,
            advances_entries.report_type,
            SUM(advances_entries.amount) as balance


            FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id=advances.id
            LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id=cash_disbursement.id
            LEFT JOIN sub_accounts_view ON advances_entries.object_code = sub_accounts_view.object_code
            LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
            GROUP BY advances.province,
            advances_entries.reporting_period,
            advances_entries.report_type

        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW IF EXISTS cdr_advances_balance')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210811_054106_create_cdr_advances_balance_view cannot be reverted.\n";

        return false;
    }
    */
}
