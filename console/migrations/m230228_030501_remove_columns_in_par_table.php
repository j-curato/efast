<?php

use yii\db\Migration;

/**
 * Class m230228_030501_remove_columns_in_par_table
 */
class m230228_030501_remove_columns_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('par', 'agency_id');
        $this->dropColumn('par', 'old_par_number');
        $this->dropColumn('par', 'location');
        $this->dropColumn('par', 'accountable_officer');
        $this->dropColumn('par', 'fk_accountable_officer_id');
        $this->dropColumn('par', 'fk_recieve_by_jocos_id');
        $this->dropColumn('par', 'recieve_by_jocos');
        $this->dropColumn('par', 'issued_by');
        $this->dropColumn('par', 'issued_to');
        $this->alterColumn('par', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->after('is_unserviceable'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('par', 'agency_id', $this->string());
        $this->addColumn('par', 'old_par_number', $this->string());
        $this->addColumn('par', 'location', $this->string());
        $this->addColumn('par', 'accountable_officer', $this->string());
        $this->addColumn('par', 'fk_accountable_officer_id', $this->string());
        $this->addColumn('par', 'fk_recieve_by_jocos_id', $this->string());
        $this->addColumn('par', 'recieve_by_jocos', $this->string());
        $this->addColumn('par', 'issued_by', $this->string());
        $this->addColumn('par', 'issued_to', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230228_030501_remove_columns_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
