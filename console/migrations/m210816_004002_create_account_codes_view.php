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
