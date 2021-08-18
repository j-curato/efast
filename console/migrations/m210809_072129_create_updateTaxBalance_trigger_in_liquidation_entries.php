<?php

use yii\db\Migration;

/**
 * Class m210809_072129_create_updateTaxBalance_trigger_in_liquidation_entries
 */
class m210809_072129_create_updateTaxBalance_trigger_in_liquidation_entries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
            CREATE TRIGGER `updateTaxBalance` AFTER INSERT ON `liquidation_entries`
            FOR EACH ROW BEGIN
            DECLARE prov CHAR(20) DEFAULT '';

            SET prov  = (SELECT advances.province FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            WHERE advances_entries.id = NEW.advances_entries_id
            );


            UPDATE liquidation_balances 
            INNER JOIN (
            SELECT
            liquidation_entries.reporting_period,
            SUM(liquidation_entries.vat_nonvat) as total_vat,
            SUM(liquidation_entries.expanded_tax) as total_expanded,
            SUM(liquidation_entries.liquidation_damage) as total_liquidation_damage
            FROM liquidation_entries
            LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
            WHERE

            advances.province = prov
            AND 
            liquidation_entries.reporting_period = NEW.reporting_period
            GROUP BY liquidation_entries.reporting_period
            ) as q ON liquidation_balances.reporting_period =  q.reporting_period

            SET liquidation_balances.total_vat_nonvat = q.total_vat,
            liquidation_balances.total_expanded= q.total_expanded,
            liquidation_balances.total_liquidation_damage  = q.total_liquidation_damage
            WHERE liquidation_balances.reporting_period = NEW.reporting_period
            AND liquidation_balances.province  = prov;
            END

        SQL;
        $this->execute($sql);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP TRIGGER updateTaxBalance')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210809_072129_create_updateTaxBalance_trigger_in_liquidation_entries cannot be reverted.\n";

        return false;
    }
    */
}
