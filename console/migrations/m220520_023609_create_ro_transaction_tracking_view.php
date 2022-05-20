<?php

use yii\db\Migration;

/**
 * Class m220520_023609_create_ro_transaction_tracking_view
 */
class m220520_023609_create_ro_transaction_tracking_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS transaction_tracking;
            CREATE VIEW transaction_tracking as 
                SELECT 
                `transaction`.id,
                `transaction`.tracking_number,
                responsibility_center.`name` division,
                `transaction`.gross_amount,
                `transaction`.transaction_date,
                payee.account_name as payee,
                `transaction`.particular,
                ors.ors_number,
                ors.ors_date,
                ors.created_at as ors_created_at,
                dv.dv_number,
                dv.recieved_at,
                dv.in_timestamp,
                dv.out_timestamp,
                cash_disbursement.check_or_ada_no,
                cash_disbursement.issuance_date,
                cash_disbursement.begin_time as cash_in,
                cash_disbursement.out_time as cash_out
                IF(cash_disbursement.is_cancelled =1,'Cancelled','Good') cash_is_cancelled
                FROM `transaction`
                LEFT JOIN payee ON `transaction`.payee_id = payee.id
                LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id  = responsibility_center.id
                LEFT JOIN  (SELECT
                process_ors.id,
                process_ors.transaction_id, 
                process_ors.date as ors_date,
                process_ors.serial_number as ors_number,
                process_ors.created_at 
                FROM process_ors
                WHERE
                process_ors.is_cancelled !=1) as ors ON `transaction`.id = ors.transaction_id
                LEFT JOIN (SELECT 
                dv_aucs.id as dv_id,
                dv_aucs.dv_number,
                dv_aucs_entries.process_ors_id,
                dv_aucs.recieved_at,
                dv_aucs.in_timestamp,
                dv_aucs.out_timestamp
                FROM dv_aucs
                LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
                WHERE dv_aucs.is_cancelled !=1) as dv ON ors.id = dv.process_ors_id
                LEFT JOIN cash_disbursement ON dv.dv_id = cash_disbursement.dv_aucs_id
                ORDER BY `transaction`.id DESC
        SQL;
        $this->execute($sql);
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
        echo "m220520_023609_create_ro_transaction_tracking_view cannot be reverted.\n";

        return false;
    }
    */
}
