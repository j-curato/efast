<?php

use yii\db\Migration;

/**
 * Class m211111_073141_create_jev_totals_view
 */
class m211111_073141_create_jev_totals_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql =<<<SQL
            DROP VIEW IF EXISTS jev_totals;
            CREATE VIEW jev_totals as 
            SELECT 
            jev_preparation.book_id,
            jev_preparation.reporting_period,

            accounting_codes.coa_object_code,
            accounting_codes.normal_balance,
            SUM(jev_accounting_entries.debit) as debit,
            SUM(jev_accounting_entries.credit) as credit

            FROM jev_accounting_entries
            LEFT JOIN accounting_codes ON jev_accounting_entries.object_code = accounting_codes.object_code
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id

            GROUP BY 
            jev_preparation.book_id,
            jev_preparation.reporting_period,
            accounting_codes.coa_object_code


        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      Yii::$app->db->createCommand("DROP VIEW IF EXISTS jev_totals")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211111_073141_create_jev_totals_view cannot be reverted.\n";

        return false;
    }
    */
}
