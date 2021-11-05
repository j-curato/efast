<?php

use yii\db\Migration;

/**
 * Class m211105_021046_rename_from_and_to_in_ptr_table
 */
class m211105_021046_rename_from_and_to_in_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('ptr', 'from', 'employee_from');
        $this->renameColumn('ptr', 'to', 'employee_to');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('ptr', 'employee_from', 'from');
        $this->renameColumn('ptr', 'employee_to', 'to');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211105_021046_rename_from_and_to_in_ptr_table cannot be reverted.\n";

        return false;
    }
    */
}
