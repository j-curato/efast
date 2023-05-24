<?php

use yii\db\Migration;

/**
 * Class m230516_023913_add_constraint_for_parent_disbursement_in_cash_disbursement_table
 */
class m230516_023913_add_constraint_for_parent_disbursement_in_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-cash-parent_disbursement', 'cash_disbursement', 'parent_disbursement');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-cash-parent_disbursement', 'cash_disbursement');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230516_023913_add_constraint_for_parent_disbursement_in_cash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}
