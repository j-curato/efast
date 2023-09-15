<?php

use yii\db\Migration;

/**
 * Class m230130_090416_add_fk_pr_allotment_id_in_transction_pr_items_table
 */
class m230130_090416_add_fk_pr_allotment_id_in_transction_pr_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction_pr_items', 'fk_pr_allotment_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction_pr_items', 'fk_pr_allotment_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230130_090416_add_fk_pr_allotment_id_in_transction_pr_items_table cannot be reverted.\n";

        return false;
    }
    */
}
