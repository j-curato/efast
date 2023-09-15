<?php

use yii\db\Migration;

/**
 * Class m230914_020300_remove_columns_in_it_helpdesk_table
 */
class m230914_020300_remove_columns_in_it_helpdesk_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-it-csf-fk_client_id', 'it_helpdesk_csf');
        $this->dropIndex('idx-it-csf-fk_client_id', 'it_helpdesk_csf');


        $this->dropColumn('it_helpdesk_csf', 'fk_client_id');
        $this->dropColumn('it_helpdesk_csf', 'contact_num');
        $this->dropColumn('it_helpdesk_csf', 'address');
        $this->dropColumn('it_helpdesk_csf', 'email');
        $this->dropColumn('it_helpdesk_csf', 'age_group');
        $this->dropColumn('it_helpdesk_csf', 'sex');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->addColumn('it_helpdesk_csf', 'contact_num', $this->integer());
        $this->addColumn('it_helpdesk_csf', 'address', $this->integer());
        $this->addColumn('it_helpdesk_csf', 'email', $this->integer());
        $this->addColumn('it_helpdesk_csf', 'age_group', $this->integer());
        $this->addColumn('it_helpdesk_csf', 'sex', $this->integer());
        $this->addColumn('it_helpdesk_csf', 'fk_client_id', $this->bigInteger());
        $this->createIndex('idx-it-csf-fk_client_id', 'it_helpdesk_csf', 'fk_client_id');
        $this->addForeignKey('fk-it-csf-fk_client_id', 'it_helpdesk_csf', 'fk_client_id', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230914_020300_remove_columns_in_it_helpdesk_table cannot be reverted.\n";

        return false;
    }
    */
}
