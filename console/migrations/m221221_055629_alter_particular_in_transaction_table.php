<?php

use yii\db\Migration;

/**
 * Class m221221_055629_alter_particular_in_transaction_table
 */
class m221221_055629_alter_particular_in_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('transaction', 'particular', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('transaction', 'particular', $this->text());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221221_055629_alter_particular_in_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
