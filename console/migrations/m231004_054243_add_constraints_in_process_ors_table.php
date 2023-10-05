<?php

use yii\db\Migration;

/**
 * Class m231004_054243_add_constraints_in_process_ors_table
 */
class m231004_054243_add_constraints_in_process_ors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->query();
        $this->addForeignKey('fk-process_ors-transaction_id', 'process_ors', 'transaction_id', 'transaction', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-process_ors-transaction_id', 'process_ors');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231004_054243_add_constraints_in_process_ors_table cannot be reverted.\n";

        return false;
    }
    */
}
