<?php

use yii\db\Migration;

/**
 * Class m230516_055410_rename_mode_of_payment_column_in_ro_check_ranges_table
 */
class m230516_055410_rename_mode_of_payment_column_in_ro_check_ranges_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('ro_check_ranges', 'mode_of_payment', 'check_type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('ro_check_ranges', 'check_type', 'mode_of_payment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230516_055410_rename_mode_of_payment_column_in_ro_check_ranges_table cannot be reverted.\n";

        return false;
    }
    */
}
