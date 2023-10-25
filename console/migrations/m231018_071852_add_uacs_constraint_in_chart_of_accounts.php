<?php

use yii\db\Migration;

/**
 * Class m231018_071852_add_uacs_constraint_in_chart_of_accounts
 */
class m231018_071852_add_uacs_constraint_in_chart_of_accounts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        // $this->addForeignKey('fk-chart-of-account-uacs', 'chart_of_accounts', 'uacs', 'uacs_object_code', 'object_code', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-chart-of-account-uacs', 'chart_of_accounts');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231018_071852_add_uacs_constraint_in_chart_of_accounts cannot be reverted.\n";

        return false;
    }
    */
}
