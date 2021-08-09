<?php

use yii\db\Migration;

/**
 * Class m210809_030703_create_updateLiquidationBalanceOnDelete_trigger
 */
class m210809_030703_create_updateLiquidationBalanceOnDelete_trigger extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql =<<<SQL
            CREATE TRIGGER updateLiquidationBalanceOnDelete AFTER DELETE ON liquidation_entries
            FOR EACH ROW
            BEGIN
            DECLARE nb INT default 0;
            DECLARE prov CHAR(20) DEFAULT '';
            DECLARE bal DECIMAL(10,2) DEFAULT 0 ;

            SET prov = (SELECT advances.province FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            WHERE advances_entries.id = OLD.advances_entries_id);



            SET BAL =		(SELECT SUM(liquidation_entries.withdrawals) 
                    FROM liquidation_entries
                    LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
                    LEFT JOIN advances ON advances_entries.advances_id = advances.id

                    WHERE 
                    advances.province = prov
                    AND liquidation_entries.reporting_period = OLD.reporting_period

                    );

            UPDATE liquidation_balances SET liquidation_balances.balance = bal
                    WHERE liquidation_balances.reporting_period = OLD.reporting_period
                        AND liquidation_balances.province =prov;





            END
        SQL;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP TRIGGER updateLiquidationBalanceOnDelete')->query();

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    
    public function up()
    {

    }

    public function down()
    {
        echo "m210809_030703_create_updateLiquidationBalanceOnDelete_trigger cannot be reverted.\n";

        return false;
    }
    */
}
