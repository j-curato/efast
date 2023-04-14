<?php

use yii\db\Migration;

/**
 * Class m230414_062024_add_transfer_reason_on_ptr_table
 */
class m230414_062024_add_transfer_reason_on_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ptr', 'transfer_reason', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ptr', 'transfer_reason');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230414_062024_add_transfer_reason_on_ptr_table cannot be reverted.\n";

        return false;
    }
    */
}
