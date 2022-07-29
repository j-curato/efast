<?php

use yii\db\Migration;

/**
 * Class m220729_024844_add_created_at_in_cash_disbursement_table
 */
class m220729_024844_add_created_at_in_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_disbursement', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cash_disbursement', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220729_024844_add_created_at_in_cash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}
