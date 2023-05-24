<?php

use yii\db\Migration;

/**
 * Class m230516_054922_add_check_type_in_mode_of_payments_table
 */
class m230516_054922_add_check_type_in_mode_of_payments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mode_of_payments', 'check_type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mode_of_payments', 'check_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230516_054922_add_check_type_in_mode_of_payments_table cannot be reverted.\n";

        return false;
    }
    */
}
