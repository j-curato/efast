<?php

use yii\db\Migration;

/**
 * Class m220614_015038_add_is_deleted_and_deleted_at_in_ro_liquidation_report_refunds_table
 */
class m220614_015038_add_is_deleted_and_deleted_at_in_ro_liquidation_report_refunds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ro_liquidation_report_refunds','is_deleted',$this->boolean()->defaultValue(false));
        $this->addColumn('ro_liquidation_report_refunds','deleted_at',$this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ro_liquidation_report_refunds','is_deleted');
        $this->dropColumn('ro_liquidation_report_refunds','deleted_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220614_015038_add_is_deleted_and_deleted_at_in_ro_liquidation_report_refunds_table cannot be reverted.\n";

        return false;
    }
    */
}
