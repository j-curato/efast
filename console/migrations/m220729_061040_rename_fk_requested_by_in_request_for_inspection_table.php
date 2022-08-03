<?php

use yii\db\Migration;

/**
 * Class m220729_061040_rename_fk_requested_by_in_request_for_inspection_table
 */
class m220729_061040_rename_fk_requested_by_in_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('request_for_inspection', 'fk_requested_by', 'fk_requested_by_division');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('request_for_inspection', 'fk_requested_by_division', 'fk_requested_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220729_061040_rename_fk_requested_by_in_request_for_inspection_table cannot be reverted.\n";

        return false;
    }
    */
}
