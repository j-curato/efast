<?php

use yii\db\Migration;

/**
 * Class m210813_032322_create_cibr_advances_balance_view
 */
class m210813_032322_create_cibr_advances_balance_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS cibr_advances_balances;
            CREATE VIEW cibr_advances_balances as 
            SELECT 
            advances.province,
            advances_entries.reporting_period,
            SUM(advances_entries.amount)  as total
            FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            WHERE
            advances_entries.is_deleted !=1
            GROUP BY advances.province
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
        Yii::$app->db->createCommand('DROP VIEW IF EXISTS cibr_advances_balances')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210813_032322_create_cibr_advances_balance_view cannot be reverted.\n";

        return false;
    }
    */
}
