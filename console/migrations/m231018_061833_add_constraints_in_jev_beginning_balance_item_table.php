<?php

use yii\db\Migration;

/**
 * Class m231018_061833_add_constraints_in_jev_beginning_balance_item_table
 */
class m231018_061833_add_constraints_in_jev_beginning_balance_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->query();
        $this->createIndex('idx-jev-bgn-bal-jev_beginning_balance_id', 'jev_beginning_balance_item', 'jev_beginning_balance_id');
        $this->addForeignKey('fk-jev-bgn-bal-jev_beginning_balance_id', 'jev_beginning_balance_item', 'jev_beginning_balance_id', 'jev_beginning_balance', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-jev-bgn-bal-jev_beginning_balance_id', 'jev_beginning_balance_item');
        $this->dropIndex('idx-jev-bgn-bal-jev_beginning_balance_id', 'jev_beginning_balance_item');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231018_061833_add_constraints_in_jev_beginning_balance_item_table cannot be reverted.\n";

        return false;
    }
    */
}
