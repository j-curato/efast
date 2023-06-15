<?php

use yii\db\Migration;

/**
 * Class m230613_022109_create_vw_dvs_in_bank_view
 */
class m230613_022109_create_vw_dvs_in_bank_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_dvs_in_bank;
       CREATE VIEW vw_dvs_in_bank  AS 
       SELECT
        cash_disbursement_items.id,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        cash_disbursement.issuance_date,
        dv_aucs_index.dv_number,
        dv_aucs_index.grossAmt,
        dv_aucs_index.payee,
        dv_aucs_index.orsNums,
        dv_aucs_index.particular,
        acics.serial_number as acic_num,
        cash_disbursement.reporting_period

        FROM acic_in_bank_items
        JOIN acics ON acic_in_bank_items.fk_acic_id = acics.id
        JOIN acics_cash_items ON acics.id = acics.id
        JOIN cash_disbursement ON acics_cash_items.fk_cash_disbursement_id = cash_disbursement.id
        JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
        JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id

        WHERE acic_in_bank_items.is_deleted = 0
        AND acics_cash_items.is_deleted  = 0 
        AND cash_disbursement_items.is_deleted  = 0
        AND NOT EXISTS (SELECT 
            rci_items.fk_cash_disbursement_item_id
            FROM rci_items
            WHERE 
            rci_items.is_deleted = 0
            AND rci_items.fk_cash_disbursement_item_id= cash_disbursement_items.id
            )
       ")
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
        echo "m230613_022109_create_vw_dvs_in_bank_view cannot be reverted.\n";

        return false;
    }
    */
}
