<?php

use yii\db\Migration;

/**
 * Class m211105_015649_rename_transfer_type_in_ptr_table
 */
class m211105_015649_rename_transfer_type_in_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('ptr','transfer_type','transfer_type_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('ptr','transfer_type_id','transfer_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211105_015649_rename_transfer_type_in_ptr_table cannot be reverted.\n";

        return false;
    }
    */
}
