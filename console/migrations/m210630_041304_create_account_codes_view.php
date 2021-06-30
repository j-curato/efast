<?php

use yii\db\Migration;

/**
 * Class m210630_041304_create_account_codes_view
 */
class m210630_041304_create_account_codes_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("CREATE VIEW account_codes AS 
        SELECT 
        chart_of_accounts.id ,
        chart_of_accounts.uacs as object_code,
        chart_of_accounts.general_ledger as account_title,
        1 as lvl
        FROM chart_of_accounts
        UNION
        SELECT
        sub_accounts1.id ,
        sub_accounts1.object_code,
        sub_accounts1.`name` as account_title,
        2 as lvl
        FROM sub_accounts1
        UNION 
        SELECT
        sub_accounts2.id ,
        sub_accounts2.object_code,
        sub_accounts2.`name` as account_title,
        3 as lvl
        FROM sub_accounts2 
        ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW account_codes ")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210630_041304_create_account_codes_view cannot be reverted.\n";

        return false;
    }
    */
}
