<?php

use yii\db\Migration;

/**
 * Class m231130_023316_add_constraints_in_check_range_table
 */
class m231130_023316_add_constraints_in_check_range_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-check_range-bank_account_id', 'check_range', 'bank_account_id');
        $this->addForeignKey('fk-check_range-bank_account_id', 'check_range', 'bank_account_id', 'bank_account', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-check_range-bank_account_id', 'check_range');
        $this->dropIndex('idx-check_range-bank_account_id', 'check_range');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231130_023316_add_constraints_in_check_range_table cannot be reverted.\n";

        return false;
    }
    */
}
