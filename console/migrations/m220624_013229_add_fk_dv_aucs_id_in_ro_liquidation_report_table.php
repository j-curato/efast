<?php

use yii\db\Migration;

/**
 * Class m220624_013229_add_fk_dv_aucs_id_in_ro_liquidation_report_table
 */
class m220624_013229_add_fk_dv_aucs_id_in_ro_liquidation_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ro_liquidation_report','fk_dv_aucs_id',$this->bigInteger()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ro_liquidation_report','fk_dv_aucs_id');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220624_013229_add_fk_dv_aucs_id_in_ro_liquidation_report_table cannot be reverted.\n";

        return false;
    }
    */
}
