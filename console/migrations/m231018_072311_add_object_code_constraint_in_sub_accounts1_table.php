<?php

use yii\db\Migration;

/**
 * Class m231018_072311_add_object_code_constraint_in_sub_accounts1_table
 */
class m231018_072311_add_object_code_constraint_in_sub_accounts1_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->addForeignKey('fk-sub_accounts1-object_code', 'sub_accounts1', 'object_code', 'uacs_object_code', 'object_code', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-sub_accounts1-object_code', 'sub_accounts1');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231018_072311_add_object_code_constraint_in_sub_accounts1_table cannot be reverted.\n";

        return false;
    }
    */
}
