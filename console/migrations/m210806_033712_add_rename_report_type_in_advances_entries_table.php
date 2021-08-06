<?php

use yii\db\Migration;

/**
 * Class m210806_033712_add_rename_report_type_in_advances_entries_table
 */
class m210806_033712_add_rename_report_type_in_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('advances_entries','report_type','advances_type');
        $this->addColumn('advances_entries','report_type',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances_entries','report_type');
        $this->renameColumn('advances_entries','advances_type','report_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210806_033712_add_rename_report_type_in_advances_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
