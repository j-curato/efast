<?php

use yii\db\Migration;

/**
 * Class m231018_072644_add_object_code_constraint_in_jev_beginning_balance_item_table
 */
class m231018_072644_add_object_code_constraint_in_jev_beginning_balance_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->createIndex('idx-jev-bgn-bal-itm-object_code', 'jev_beginning_balance_item', 'object_code');
        $this->addForeignKey('fk-jev-bgn-bal-itm-object_code', 'jev_beginning_balance_item', 'object_code', 'uacs_object_codes', 'object_code', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-jev-bgn-bal-itm-object_code', 'jev_beginning_balance_item');
        $this->dropIndex('idx-jev-bgn-bal-itm-object_code', 'jev_beginning_balance_item');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231018_072644_add_object_code_constraint_in_jev_beginning_balance_item_table cannot be reverted.\n";

        return false;
    }
    */
}
