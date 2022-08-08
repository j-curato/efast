<?php

use yii\db\Migration;

/**
 * Class m220805_004905_remove_fk_request_for_inspection_id_in_inspection_report_table
 */
class m220805_004905_remove_fk_request_for_inspection_id_in_inspection_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('inspection_report', 'fk_request_for_inspection_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('inspection_report', 'fk_request_for_inspection_id', $this->bigInteger());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220805_004905_remove_fk_request_for_inspection_id_in_inspection_report_table cannot be reverted.\n";

        return false;
    }
    */
}
