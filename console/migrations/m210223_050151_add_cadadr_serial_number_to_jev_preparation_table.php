<?php

use yii\db\Migration;

/**
 * Class m210223_050151_add_cadadr_serial_number_to_jev_preparation_table
 */
class m210223_050151_add_cadadr_serial_number_to_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_preparation', 'cadadr_serial_number', $this->string(255));
        $this->addColumn('jev_preparation', 'check_ada', $this->string(255));
        $this->addColumn('jev_preparation', 'check_ada_number', $this->string(255));
        $this->addColumn('jev_preparation', 'check_ada_date', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_preparation', 'cadadr_serial_number');
        $this->dropColumn('jev_preparation', 'check_ada');
        $this->dropColumn('jev_preparation', 'check_ada_number');
        $this->dropColumn('jev_preparation', 'check_ada_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210223_050151_add_cadadr_serial_number_to_jev_preparation_table cannot be reverted.\n";

        return false;
    }
    */
}
