<?php

use yii\db\Migration;

/**
 * Class m230717_060504_remove_report_type_col_in_advances_table
 */
class m230717_060504_remove_report_type_col_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('advances', 'report_type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('advances', 'report_type', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230717_060504_remove_report_type_col_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
