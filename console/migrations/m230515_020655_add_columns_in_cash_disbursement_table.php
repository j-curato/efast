<?php

use yii\db\Migration;

/**
 * Class m230515_020655_add_columns_in_cash_disbursement_table
 */
class m230515_020655_add_columns_in_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_disbursement', 'fk_mode_of_payment_id', $this->integer());
        $this->createIndex('idx-cash-fk_mode_of_payment_id', 'cash_disbursement', 'fk_mode_of_payment_id');
        $this->addForeignKey('fk-csh-fk_mode_of_payment_id', 'cash_disbursement', 'fk_mode_of_payment_id', 'mode_of_payments', 'id', 'RESTRICT');

        $this->addColumn('cash_disbursement', 'fk_ro_check_range_id', $this->integer());
        $this->createIndex('idx-cash-fk_ro_check_range_id', 'cash_disbursement', 'fk_ro_check_range_id');
        $this->addForeignKey('fk-cash-fk_ro_check_range_id', 'cash_disbursement', 'fk_ro_check_range_id', 'ro_check_ranges', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-csh-fk_mode_of_payment_id', 'cash_disbursement');
        $this->dropForeignKey('fk-cash-fk_ro_check_range_id', 'cash_disbursement');

        $this->dropIndex('idx-cash-fk_mode_of_payment_id', 'cash_disbursement');
        $this->dropIndex('idx-cash-fk_ro_check_range_id', 'cash_disbursement');

        $this->dropColumn('cash_disbursement', 'fk_mode_of_payment_id');
        $this->dropColumn('cash_disbursement', 'fk_ro_check_range_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230515_020655_add_columns_in_cash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}
