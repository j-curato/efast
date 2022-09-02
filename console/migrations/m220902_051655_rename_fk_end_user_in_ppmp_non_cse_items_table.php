<?php

use yii\db\Migration;

/**
 * Class m220902_051655_rename_fk_end_user_in_ppmp_non_cse_items_table
 */
class m220902_051655_rename_fk_end_user_in_ppmp_non_cse_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('ppmp_non_cse_items', 'fk_end_user', 'fk_responsibility_center_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('ppmp_non_cse_items', 'fk_responsibility_center_id', 'fk_end_user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220902_051655_rename_fk_end_user_in_ppmp_non_cse_items_table cannot be reverted.\n";

        return false;
    }
    */
}
