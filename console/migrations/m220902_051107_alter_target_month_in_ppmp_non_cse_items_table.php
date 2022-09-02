<?php

use yii\db\Migration;

/**
 * Class m220902_051107_alter_target_month_in_ppmp_non_cse_items_table
 */
class m220902_051107_alter_target_month_in_ppmp_non_cse_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('ppmp_non_cse_items', 'target_month', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->alterColumn('ppmp_non_cse_items', 'target_month', $this->text());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220902_051107_alter_target_month_in_ppmp_non_cse_items_table cannot be reverted.\n";

        return false;
    }
    */
}
