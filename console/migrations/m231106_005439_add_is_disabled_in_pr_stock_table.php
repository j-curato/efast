<?php

use yii\db\Migration;

/**
 * Class m231106_005439_add_is_disabled_in_pr_stock_table
 */
class m231106_005439_add_is_disabled_in_pr_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_stock', 'is_disabled', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_stock', 'is_disabled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231106_005439_add_is_disabled_in_pr_stock_table cannot be reverted.\n";

        return false;
    }
    */
}
