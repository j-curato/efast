<?php

use yii\db\Migration;

/**
 * Class m210809_030640_create_updateLiquidationBalanceOnInsert_trigger
 */
class m210809_030640_create_updateLiquidationBalanceOnInsert_trigger extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            CREATE TRIGGER `updateLiquidationBalanceOnInsert` AFTER INSERT ON `liquidation_entries`
            FOR EACH ROW BEGIN
            DECLARE nb INT default 0;
            DECLARE prov CHAR(20) DEFAULT '';
            DECLARE bal DECIMAL(10,2) DEFAULT 0 ;

            SET prov = (SELECT advances.province FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            WHERE advances_entries.id = NEW.advances_entries_id);


            SET nb = (SELECT EXISTS(SELECT * from liquidation_balances 
            WHERE liquidation_balances.reporting_period=NEW.reporting_period
            AND liquidation_balances.province = prov
            ));

            SET BAL =		(SELECT SUM(liquidation_entries.withdrawals) 
                    FROM liquidation_entries
                    LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
                    LEFT JOIN advances ON advances_entries.advances_id = advances.id
                
                            WHERE 
                    advances.province = prov
                    AND liquidation_entries.reporting_period = NEW.reporting_period

                    );
            IF(nb > 0) THEN
            UPDATE liquidation_balances SET liquidation_balances.balance = bal
                    WHERE liquidation_balances.reporting_period = NEW.reporting_period
                        AND liquidation_balances.province =prov;
            ELSE
            INSERT INTO liquidation_balances (liquidation_balances.reporting_period,liquidation_balances.balance,liquidation_balances.province)
                    VALUE (NEW.reporting_period,bal,prov);
            END IF;




            END

        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP TRIGGER updateLiquidationBalanceOnInsert')->query();
        
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210809_030640_create_updateLiquidationBalanceOnInsert_trigger cannot be reverted.\n";

        return false;
    }
    */
}
