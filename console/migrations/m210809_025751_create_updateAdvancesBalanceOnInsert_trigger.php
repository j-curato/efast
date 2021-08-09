<?php

use yii\db\Migration;

/**
 * Class m210809_025751_create_updateAdvancesBalanceOnInsert_trigger
 */
class m210809_025751_create_updateAdvancesBalanceOnInsert_trigger extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
           CREATE TRIGGER updateAdvancesBalanceOnInsert AFTER INSERT ON advances_entries
            FOR EACH ROW
            BEGIN 
                DECLARE nb INT default 0;
                DECLARE prov CHARACTER(20) DEFAULT '';
                DECLARE bal DECIMAL(19,2) DEFAULT 0 ;

                SET prov = (SELECT advances.province FROM advances WHERE id = NEW.advances_id);
                SET nb = (SELECT EXISTS(SELECT * from advances_balances WHERE advances_balances.reporting_period=NEW.reporting_period
                AND advances_balances.province= prov));

                SET BAL =(SELECT ROUND(SUM(advances_entries.amount),2) FROM advances_entries
                        LEFT JOIN advances ON advances_entries.advances_id = advances.id
                        WHERE 
                        advances.province = prov
                        AND advances_entries.reporting_period = NEW.reporting_period
                        );
                IF(nb > 0) THEN
                UPDATE advances_balances SET advances_balances.balance = bal

                        WHERE advances_balances.reporting_period = NEW.reporting_period
                        AND advances_balances.province = prov;
                ELSE
                INSERT INTO advances_balances (advances_balances.reporting_period,advances_balances.balance,advances_balances.province)
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
        Yii::$app->db->createCommand('DROP TRIGGER updateAdvancesBalanceOnInsert')->query();

      
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210809_025751_create_updateAdvancesBalanceOnInsert_trigger cannot be reverted.\n";

        return false;
    }
    */
}
