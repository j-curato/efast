<?php

use yii\db\Migration;

/**
 * Class m210916_013233_create_liquidation_balance_per_advances_view
 */
class m210916_013233_create_liquidation_balance_per_advances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $query =<<<SQL
            DROP VIEW IF EXISTS liquidation_balance_per_advances;
            CREATE VIEW liquidation_balance_per_advances as 
            SELECT 
            liquidation_entries.advances_entries_id,
            liquidation_entries.reporting_period,
            SUM(liquidation_entries.withdrawals) as total_withdrawals
            FROM liquidation_entries
            GROUP BY liquidation_entries.advances_entries_id,
            liquidation_entries.reporting_period

        SQL;
        $this->execute($query);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS liquidation_balance_per_advances")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210916_013233_create_liquidation_balance_per_advances_view cannot be reverted.\n";

        return false;
    }
    */
}
