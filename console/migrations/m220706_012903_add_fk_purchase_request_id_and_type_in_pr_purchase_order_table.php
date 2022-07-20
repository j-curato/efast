<?php

use yii\db\Migration;

/**
 * Class m220706_012903_add_fk_purchase_request_id_and_type_in_pr_purchase_order_table
 */
class m220706_012903_add_fk_purchase_request_id_and_type_in_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_order', 'fk_purchase_request_id', $this->bigInteger()->defaultValue(null));
        $this->addColumn('pr_purchase_order', 'type', $this->string()->defaultValue('with abstract'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_order', 'fk_purchase_request_id');
        $this->dropColumn('pr_purchase_order', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220706_012903_add_fk_purchase_request_id_and_type_in_pr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
