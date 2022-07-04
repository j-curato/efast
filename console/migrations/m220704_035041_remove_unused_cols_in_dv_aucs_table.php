<?php

use yii\db\Migration;

/**
 * Class m220704_035041_remove_unused_cols_in_dv_aucs_table
 */
class m220704_035041_remove_unused_cols_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('dv_aucs', 'tax_withheld');
        $this->dropColumn('dv_aucs', 'other_trust_liability_withheld');
        $this->dropColumn('dv_aucs', 'net_amount_paid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('dv_aucs', 'tax_withheld', $this->integer());
        $this->addColumn('dv_aucs', 'other_trust_liability_withheld', $this->integer());
        $this->addColumn('dv_aucs', 'net_amount_paid', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220704_035041_remove_unused_cols_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
