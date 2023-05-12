<?php

use yii\db\Migration;

/**
 * Class m230511_003719_add_is_cancelled_in_pr_purchase_order_table
 */
class m230511_003719_add_is_cancelled_in_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_order', 'is_cancelled', $this->boolean()->defaultValue(0));
        $this->addColumn('pr_purchase_order', 'cancelled_at', $this->timestamp()->defaultValue(NULL)->after('is_cancelled'));
        $this->alterColumn('pr_purchase_order', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->after('is_cancelled'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_order', 'is_cancelled');
        $this->dropColumn('pr_purchase-oder', 'cancelled_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230511_003719_add_is_cancelled_in_pr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
