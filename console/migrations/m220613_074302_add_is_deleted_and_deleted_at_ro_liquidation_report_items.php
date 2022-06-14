<?php

use yii\db\Migration;

/**
 * Class m220613_074302_add_is_deleted_and_deleted_at_ro_liquidation_report_items
 */
class m220613_074302_add_is_deleted_and_deleted_at_ro_liquidation_report_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ro_liquidation_report_items', 'is_deleted', $this->boolean()->defaultValue(0));
        $this->addColumn('ro_liquidation_report_items', 'deleted_at', $this->timestamp()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ro_liquidation_report_items', 'is_deleted');
        $this->dropColumn('ro_liquidation_report_items', 'deleted_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220613_074302_add_is_deleted_and_deleted_at_ro_liquidation_report_items cannot be reverted.\n";

        return false;
    }
    */
}
