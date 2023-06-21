<?php

use yii\db\Migration;

/**
 * Class m230621_065831_create_vw_radai_form_lddap_adas_view
 */
class m230621_065831_create_vw_radai_form_lddap_adas_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_radai_form_lddap_adas;
        CREATE VIEW vw_radai_form_lddap_adas as 
        SELECT
        lddap_adas.id,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.issuance_date,
        lddap_adas.serial_number as lddap_no,
        mode_of_payments.`name` as mode_of_payment_name,
        acics.serial_number as acic_no

        FROM cash_disbursement
        JOIN acics_cash_items ON cash_disbursement.id = acics_cash_items.fk_cash_disbursement_id
        JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
        JOIN lddap_adas ON cash_disbursement.id = lddap_adas.fk_cash_disbursement_id
        JOIN acics ON acics_cash_items.fk_acic_id = acics.id
        JOIN acic_in_bank_items ON acics.id = acic_in_bank_items.fk_acic_id
        WHERE 
        (mode_of_payments.`name` LIKE '%eCheck w/ ADA%' OR mode_of_payments.`name` LIKE '%LBP Check w/ ADA%')
        AND acics_cash_items.is_deleted = 0
        AND acic_in_bank_items.is_deleted = 0
        AND NOT EXISTS (SELECT radai_items.fk_lddap_ada_id FROM radai_items WHERE radai_items.is_deleted = 0 AND radai_items.fk_lddap_ada_id =  lddap_adas.id )
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
        echo "m230621_065831_create_vw_radai_form_lddap_adas_view cannot be reverted.\n";

        return false;
    }
    */
}
