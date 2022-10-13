<?php

use yii\db\Migration;

/**
 * Class m221012_083118_add_par_details_in_par_table
 */
class m221012_083118_add_par_details_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('par', 'old_par_number', $this->string());
        $this->addColumn('par', 'location', $this->string());
        $this->addColumn('par', 'accountable_officer', $this->string());
        $this->addColumn('par', 'fk_accountable_officer_id', $this->bigInteger());
        $this->addColumn('par', 'fk_recieve_by_jocos_id', $this->bigInteger());
        $this->addColumn('par', 'recieve_by_jocos', $this->string());
        $this->addColumn('par', 'issued_by', $this->string());
        $this->addColumn('par', 'fk_issued_by_id', $this->bigInteger());
        $this->addColumn('par', 'issued_to', $this->string());
        $this->addColumn('par', 'remarks', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221012_083118_add_par_details_in_par_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221012_083118_add_par_details_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
