<?php

use yii\db\Migration;

/**
 * Class m220705_064803_add_is_cloud_in_pr_project_procurement_table
 */
class m220705_064803_add_is_cloud_in_pr_project_procurement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_project_procurement','is_cloud',$this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_project_procurement','is_cloud');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220705_064803_add_is_cloud_in_pr_project_procurement_table cannot be reverted.\n";

        return false;
    }
    */
}
