<?php

use yii\db\Migration;

/**
 * Class m240206_063948_add_constraints_in_liquidation_report_table
 */
class m240206_063948_add_constraints_in_liquidation_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->alterColumn('ro_liquidation_report', 'fk_dv_aucs_id', $this->integer());
        $this->createIndex('idx-ro_liquidation_report-fk_dv_aucs_id', 'ro_liquidation_report', 'fk_dv_aucs_id');
        $this->addForeignKey('fk-ro_liquidation_report-fk_dv_aucs_id', 'ro_liquidation_report', 'fk_dv_aucs_id', 'dv_aucs', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ro_liquidation_report-fk_dv_aucs_id', 'ro_liquidation_report');
        $this->dropIndex('idx-ro_liquidation_report-fk_dv_aucs_id', 'ro_liquidation_report');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_063948_add_constraints_in_liquidation_report_table cannot be reverted.\n";

        return false;
    }
    */
}
