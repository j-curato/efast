<?php

use yii\db\Migration;

/**
 * Class m220809_021754_rename_fk_requested_by_division_in_request_for_inspection_table
 */
class m220809_021754_rename_fk_requested_by_division_in_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('request_for_inspection', 'fk_requested_by_division', 'fk_pr_office_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('request_for_inspection', 'fk_pr_office_id', 'fk_requested_by_division');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220809_021754_rename_fk_requested_by_division_in_request_for_inspection_table cannot be reverted.\n";

        return false;
    }
    */
}
