<?php

use yii\db\Migration;

/**
 * Class m210630_041304_create_account_codes_view
 */
class m210816_004002_create_account_codes_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("
        DROP VIEW  IF EXISTS accounting_codes ;
        CREATE VIEW  accounting_codes AS 
                                    SELECT 
chart_of_accounts.uacs as object_code,
chart_of_accounts.general_ledger as account_title ,
chart_of_accounts.is_active,
major_accounts.`object_code` as major_object_code,
chart_of_accounts.account_group,
chart_of_accounts.current_noncurrent,
chart_of_accounts.uacs as coa_object_code,
chart_of_accounts.general_ledger as coa_account_title,
chart_of_accounts.normal_balance,
chart_of_accounts.is_active as coa_is_active,
chart_of_accounts.is_active as sub_account_is_active



FROM chart_of_accounts,major_accounts
WHERE chart_of_accounts.major_account_id = major_accounts.id

UNION 

SELECT sub_accounts1.object_code,
sub_accounts1.`name`as account_title ,
sub_accounts1.is_active,
major_accounts.`object_code` as major_object_code,
chart_of_accounts.account_group,chart_of_accounts.current_noncurrent,
chart_of_accounts.uacs as coa_object_code,
chart_of_accounts.general_ledger as coa_account_title,
chart_of_accounts.normal_balance,
chart_of_accounts.is_active as coa_is_active,
chart_of_accounts.is_active as sub_account_is_active
FROM sub_accounts1,chart_of_accounts,major_accounts
WHERE sub_accounts1.chart_of_account_id = chart_of_accounts.id
AND chart_of_accounts.major_account_id = major_accounts.id

UNION 

SELECT sub_accounts2.object_code,
sub_accounts2.`name`as account_title,
sub_accounts2.is_active,
major_accounts.`object_code` as major_object_code,
chart_of_accounts.account_group,chart_of_accounts.current_noncurrent,
chart_of_accounts.uacs as coa_object_code,
chart_of_accounts.general_ledger as coa_account_title,
chart_of_accounts.normal_balance,
chart_of_accounts.is_active as coa_is_active,
sub_accounts1.is_active as sub_account_is_active
 FROM sub_accounts2,sub_accounts1,chart_of_accounts,major_accounts

WHERE sub_accounts2.sub_accounts1_id = sub_accounts1.id
AND sub_accounts1.chart_of_account_id = chart_of_accounts.id 
AND chart_of_accounts.major_account_id = major_accounts.id 
        ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW accounting_codes ")->query();
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
