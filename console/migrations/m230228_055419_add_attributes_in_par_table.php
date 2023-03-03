<?php

use yii\db\Migration;

/**
 * Class m230228_055419_add_attributes_in_par_table
 */
class m230228_055419_add_attributes_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('par', '_year', $this->integer()->notNull());
        $this->addColumn('par', 'fk_office_id', $this->integer());
        $this->renameColumn('par', 'fk_recieved_by', 'fk_received_by');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('par', '_year');
        $this->dropColumn('par', 'fk_office_id');
        $this->renameColumn('par', 'fk_received_by', 'fk_recieved_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230228_055419_add_attributes_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
