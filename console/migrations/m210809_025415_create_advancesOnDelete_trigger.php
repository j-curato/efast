<?php

use yii\db\Migration;

/**
 * Class m210809_025415_create_advancesOnDelete_trigger
 */
class m210809_025415_create_advancesOnDelete_trigger extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql =<<< SQL
            CREATE TRIGGER advancesOnDelete AFTER DELETE ON advances_entries
                FOR EACH ROW
                BEGIN 
                DECLARE nb INT default 0;
                DECLARE prov CHARACTER(20) DEFAULT '';
                DECLARE bal DECIMAL(20,2) DEFAULT 0 ;

                SET prov = (SELECT advances.province FROM advances WHERE id = OLD.advances_id);
                SET nb = (SELECT EXISTS(SELECT * from advances_balances WHERE advances_balances.reporting_period=OLD.reporting_period
                AND advances_balances.province= prov));

                SET BAL =(SELECT SUM(advances_entries.amount) FROM advances_entries
                        LEFT JOIN advances ON advances_entries.advances_id = advances.id
                        WHERE 
                        advances.province = prov
                        AND advances_entries.reporting_period = OLD.reporting_period
                        );

                UPDATE advances_balances SET advances_balances.balance = bal

                        WHERE advances_balances.reporting_period = OLD.reporting_period
                        AND advances_balances.province = prov;
                END 
        SQL;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP TRIGGER advancesOnDelete')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210809_025415_create_advancesOnDelete_trigger cannot be reverted.\n";

        return false;
    }
    */
}
