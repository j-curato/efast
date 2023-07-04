<?php

use yii\db\Migration;

/**
 * Class m210706_052525_create_detailed_cash_view
 */
class m210706_052525_create_detailed_cash_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand(" DROP VIEW IF EXISTS  detailed_cash_view;
        CREATE VIEW detailed_cash_view as 
                                SELECT 
                        cash_disbursement.id as cash_id,
                        cash_disbursement.book_id,
                        dv_aucs.dv_number,
                        dv_aucs.id as dv_aucs_id,
                        dv_aucs.payee_id,
                        dv_aucs.particular,
                        dv_aucs.reporting_period,
                        cash_disbursement.check_or_ada_no,
                        cash_disbursement.issuance_date,
                        cash_disbursement.mode_of_payment,
                        cash_disbursement.ada_number,
                        responsibility_center.id as rc_id,
                        `transaction`.id as transaction_id,
                        dv.total_disbursed,
                        jev_preparation.id as jev_id
                        from cash_disbursement
                        LEFT JOIN jev_preparation ON  cash_disbursement.id = jev_preparation.cash_disbursement_id
                        LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
                        LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
                        LEFT JOIN process_ors ON  dv_aucs_entries.process_ors_id = process_ors.id
                        LEFT JOIN  `transaction` ON process_ors.transaction_id = `transaction`.id
                        LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id = responsibility_center.id
                        LEFT JOIN (
                        SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,dv_aucs_entries.dv_aucs_id
                        FROM dv_aucs_entries GROUP BY dv_aucs_entries.dv_aucs_id
                        )as dv ON cash_disbursement.dv_aucs_id = dv.dv_aucs_id 
                        ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW detailed_cash_view")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210706_052525_create_detailed_cash_view cannot be reverted.\n";

        return false;
    }
    */
}
