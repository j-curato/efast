<?php

use yii\db\Migration;

/**
 * Class m230620_023417_create_vw_no_file_link_dvs_view
 */
class m230620_023417_create_vw_no_file_link_dvs_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_no_file_link_dvs;
        CREATE VIEW  vw_no_file_link_dvs AS SELECT 
        dv_aucs.id,
        dv_aucs.dv_number 
        FROM dv_aucs
        JOIN dv_aucs_file ON dv_aucs.id = dv_aucs_file.fk_dv_aucs_id
        WHERE 
        dv_aucs.dv_link IS NULL
        OR dv_aucs.dv_link = ''")
            ->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230620_023417_create_vw_no_file_link_dvs_view cannot be reverted.\n";

        return false;
    }
    */
}
