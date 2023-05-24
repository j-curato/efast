<?php

use yii\db\Migration;

/**
 * Class m230515_012930_add_mode_of_payement_in_ro_check_range_table
 */
class m230515_012930_add_mode_of_payement_in_ro_check_range_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ro_check_range', 'mode_of_payment', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ro_check_range', 'mode_of_payment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230515_012930_add_mode_of_payement_in_ro_check_range_table cannot be reverted.\n";

        return false;
    }
    */
}
