<?php

use yii\db\Migration;

/**
 * Class m230914_015317_add_outcome_in_it_heldesk_csf_table
 */
class m230914_015317_add_outcome_in_it_heldesk_csf_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('it_helpdesk_csf', 'outcome', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('it_helpdesk_csf', 'outcome');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230914_015317_add_outcome_in_it_heldesk_csf_table cannot be reverted.\n";

        return false;
    }
    */
}
