<?php

use yii\db\Migration;

/**
 * Class m220809_021559_add_fk_unit_head_in_pr_office_table
 */
class m220809_021559_add_fk_unit_head_in_pr_office_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_office', 'fk_unit_head', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_office', 'fk_unit_head');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220809_021559_add_fk_unit_head_in_pr_office_table cannot be reverted.\n";

        return false;
    }
    */
}
