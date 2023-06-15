<?php

use yii\db\Migration;

/**
 * Class m230608_025132_add_is_deleted_in_cash_disbursement_table
 */
class m230608_025132_add_is_deleted_in_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_disbursement', 'is_deleted', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cash_disbursement', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230608_025132_add_is_deleted_in_cash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}
