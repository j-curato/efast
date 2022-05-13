<?php

use yii\db\Migration;

/**
 * Class m220513_015134_add_is_final_in_pr_purchase_request_table
 */
class m220513_015134_add_is_final_in_pr_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request', 'is_final', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_request', 'is_final');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220513_015134_add_is_final_in_pr_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
