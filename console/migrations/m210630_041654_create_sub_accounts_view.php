<?php

use yii\db\Migration;

/**
 * Class m210630_042212_create_sub_accounts_view
 */
class m210630_041654_create_sub_accounts_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand(" DROP VIEW IF EXISTS sub_accounts_view;
        CREATE VIEW sub_accounts_view AS 
        SELECT Row_number() OVER(ORDER BY object_code DESC) AS 'row_number',q.object_code,q.account_title FROM 
(SELECT sub_accounts1.object_code,sub_accounts1.`name`as account_title FROM sub_accounts1
UNION 
SELECT sub_accounts2.object_code,sub_accounts2.`name`as account_title FROM sub_accounts2
) AS q ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW sub_accounts_view")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210630_042212_create_sub_accounts_view cannot be reverted.\n";

        return false;
    }
    */
}
