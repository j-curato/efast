<?php

use yii\db\Migration;

/**
 * Class m211022_063308_create_liquidation_for_cdj_view
 */
class m211022_063308_create_liquidation_for_cdj_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql =<<<SQL
        DROP VIEW IF EXISTS liquidation_for_cdj;
        CREATE VIEW liquidation_for_cdj as 
        SELECT
        liquidation.province,
        liquidation_entries.reporting_period,
        advances_entries.report_type,
        IFNULL(liquidation_entries.new_chart_of_account_id,liquidation_entries.chart_of_account_id) as chart_of_account_id ,
        liquidation_entries.withdrawals,
        liquidation_entries.vat_nonvat,
        liquidation_entries.expanded_tax
        FROM liquidation_entries
        LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
        LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id

        SQL;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      Yii::$app->db->createCommand("DROP VIEW IF EXISTS liquidation_for_cdj")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211022_063308_create_liquidation_for_cdj_view cannot be reverted.\n";

        return false;
    }
    */
}
