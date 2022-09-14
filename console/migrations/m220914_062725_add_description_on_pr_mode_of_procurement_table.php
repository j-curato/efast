<?php

use yii\db\Migration;

/**
 * Class m220914_062725_add_description_on_pr_mode_of_procurement_table
 */
class m220914_062725_add_description_on_pr_mode_of_procurement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_mode_of_procurement', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('pr_mode_of_procurement', 'description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220914_062725_add_description_on_pr_mode_of_procurement_table cannot be reverted.\n";

        return false;
    }
    */
}
