<?php

use yii\db\Migration;

/**
 * Class m230206_070554_add_constraints_in_transaction_iars_table
 */
class m230206_070554_add_constraints_in_transaction_iars_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // YIi::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        // $this->createIndex('idx-fk_transaction_id', 'transaction_iars', 'fk_transaction_id');
        // $this->addForeignKey('fk-fk_transaction_id', 'transaction_iars', 'fk_transaction_id', 'transaction', 'id', 'CASCADE');
        // YIi::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-fk_transaction_id', 'transaction_iars');
        $this->dropIndex('idx-fk_transaction_id', 'transaction_iars');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230206_070554_add_constraints_in_transaction_iars_table cannot be reverted.\n";

        return false;
    }
    */
}
