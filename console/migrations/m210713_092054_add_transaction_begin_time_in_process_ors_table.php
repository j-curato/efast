<?php

use yii\db\Migration;

/**
 * Class m210713_041221_add_transaction_begin_time_in_process_ors_table
 */
class m210713_092054_add_transaction_begin_time_in_process_ors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('process_ors', 'transaction_begin_time', $this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('process_ors', 'transaction_begin_time');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210713_041221_add_transaction_begin_time_in_process_ors_table cannot be reverted.\n";

        return false;
    }
    */
}
