<?php

use yii\db\Migration;

/**
 * Class m230621_012507_create_vw_transmittal_form_dvs_view
 */
class m230621_012507_create_vw_transmittal_form_dvs_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_transmittal_form_dvs;
        CREATE VIEW vw_transmittal_form_dvs as SELECT 
        dv_aucs.id,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        cash_disbursement.reporting_period,
        payee.account_name as payee,
        dv_aucs.particular,
        dv_aucs.dv_number,
        t_dv.amtDisbursed,
        t_dv.taxWitheld,
        cash_disbursement.is_cancelled
        FROM dv_aucs
        JOIN cash_disbursement_items ON dv_aucs.id = cash_disbursement_items.fk_dv_aucs_id
        JOIN cash_disbursement ON cash_disbursement_items.fk_cash_disbursement_id = cash_disbursement.id
        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
        LEFT JOIN (SELECT 
        dv_aucs_entries.dv_aucs_id,
        SUM(dv_aucs_entries.amount_disbursed)as amtDisbursed,
        SUM(COALESCE(dv_aucs_entries.vat_nonvat,0) + COALESCE(dv_aucs_entries.ewt_goods_services,0)+COALESCE(dv_aucs_entries.compensation,0))as taxWitheld
        FROM dv_aucs_entries 
        WHERE dv_aucs_entries.is_deleted = 0
        GROUP BY dv_aucs_entries.dv_aucs_id ) as t_dv ON dv_aucs.id = t_dv.dv_aucs_id 
        WHERE 
         NOT EXISTS (SELECT transmittal_entries.fk_dv_aucs_id FROM transmittal_entries WHERE transmittal_entries.is_deleted = 0 AND transmittal_entries.fk_dv_aucs_id = dv_aucs.id)
        AND cash_disbursement_items.is_deleted = 0
        AND dv_aucs.is_cancelled = 0
        AND NOT EXISTS (SELECT cncl_chks.parent_disbursement
        FROM cash_disbursement as cncl_chks 
        WHERE cncl_chks.parent_disbursement = cash_disbursement.id 
        AND cncl_chks.is_cancelled = 1 
        AND cncl_chks.parent_disbursement IS NOT NULL) 
        AND cash_disbursement.is_cancelled = 0
        
        ")->query();
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
        echo "m230621_012507_create_vw_transmittal_form_dvs_view cannot be reverted.\n";

        return false;
    }
    */
}
