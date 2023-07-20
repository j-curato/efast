<?php

use yii\db\Migration;

/**
 * Class m230720_011032_alter_id_in_pr_aoq_table
 */
class m230720_011032_alter_id_in_pr_aoq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->alterColumn('pr_aoq_entries', 'pr_aoq_id', $this->bigInteger());
        $this->alterColumn('pr_aoq', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230720_011032_alter_id_in_pr_aoq_table cannot be reverted.\n";

        return false;
    }
    */
}
