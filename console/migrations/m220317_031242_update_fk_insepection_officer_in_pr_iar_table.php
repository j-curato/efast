<?php

use yii\db\Migration;

/**
 * Class m220317_031242_update_fk_insepection_officer_in_pr_iar_table
 */
class m220317_031242_update_fk_insepection_officer_in_pr_iar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->renameColumn('pr_iar', 'fk_insepection_officer', 'fk_inspection_officer');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('pr_iar', 'fk_inspection_officer', 'fk_insepection_officer');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220317_031242_update_fk_insepection_officer_in_pr_iar_table cannot be reverted.\n";

        return false;
    }
    */
}
