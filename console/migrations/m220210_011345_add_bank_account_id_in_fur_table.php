<?php

use yii\db\Migration;

/**
 * Class m220210_011345_add_bank_account_id_in_fur_table
 */
class m220210_011345_add_bank_account_id_in_fur_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('fur', 'bank_account_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('fur', 'bank_account_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220210_011345_add_bank_account_id_in_fur_table cannot be reverted.\n";

        return false;
    }
    */
}
