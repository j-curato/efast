<?php

use yii\db\Migration;

/**
 * Class m210707_060724_create_for_transmittal_view
 */
class m210707_060724_create_for_transmittal_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    Yii::$app->db->createCommand("DROP VIEW IF EXISTS for_transmittal;
    CREATE VIEW for_transmittal as 
            SELECT 
            cash_disbursement.id,
            cash_disbursement.check_or_ada_no,
            cash_disbursement.ada_number,
            cash_disbursement.reporting_period,
            payee.account_name,
            dv_aucs.particular,
            dv_aucs.dv_number,
            t_dv.total_dv


            FROM cash_disbursement
            INNER JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
            INNER JOIN payee ON dv_aucs.payee_id= payee.id
            LEFT JOIN (SELECT SUM(dv_aucs_entries.amount_disbursed)as total_dv,dv_aucs_entries.dv_aucs_id FROM dv_aucs_entries GROUP BY dv_aucs_entries.dv_aucs_id )
            as t_dv ON dv_aucs.id = t_dv.dv_aucs_id 

            WHERE 
            cash_disbursement.id NOT IN (SELECT transmittal_entries.cash_disbursement_id FROM transmittal_entries)
            AND cash_disbursement.is_cancelled=0 
            ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW for_transmittal")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210707_060724_create_for_transmittal_view cannot be reverted.\n";

        return false;
    }
    */
}
