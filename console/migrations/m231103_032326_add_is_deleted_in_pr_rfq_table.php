<?php

use yii\db\Migration;

/**
 * Class m231103_032326_add_is_deleted_in_pr_rfq_table
 */
class m231103_032326_add_is_deleted_in_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_rfq', 'is_deleted', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_rfq', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231103_032326_add_is_deleted_in_pr_rfq_table cannot be reverted.\n";

        return false;
    }
    */
}
