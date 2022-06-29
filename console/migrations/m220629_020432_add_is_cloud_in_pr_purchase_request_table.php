<?php

use yii\db\Migration;

/**
 * Class m220629_020432_add_is_cloud_in_pr_purchase_request_table
 */
class m220629_020432_add_is_cloud_in_pr_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request', 'is_cloud', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_request', 'is_cloud');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220629_020432_add_is_cloud_in_pr_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
