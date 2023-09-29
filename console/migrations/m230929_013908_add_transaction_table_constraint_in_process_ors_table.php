<?php

use yii\db\Migration;

/**
 * Class m230929_013908_add_transaction_table_constraint_in_process_ors_table
 */
class m230929_013908_add_transaction_table_constraint_in_process_ors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();
        $this->createIndex('idx-process-ors_', 'process_ors', 'transaction_id');
        $this->addForeignKey('fk-process-ors_', 'process_ors', 'transaction_id', 'transaction', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-process-ors_', 'process_ors');
        $this->dropIndex('idx-process-ors_', 'process_ors');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_013908_add_transaction_table_constraint_in_process_ors_table cannot be reverted.\n";

        return false;
    }
    */
}
