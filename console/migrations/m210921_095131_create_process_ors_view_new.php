<?php

use yii\db\Migration;

/**
 * Class m210921_095131_create_process_ors_view_new
 */
class m210921_095131_create_process_ors_view_new extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            CREATE VIEW process_ors_new_view as 
                SELECT
                process_ors.id,
                process_ors.serial_number,
                `transaction`.tracking_number,
                payee.account_name as payee,
                `transaction`.particular,
                allotment_account.uacs as allotment_uacs,
                allotment_account.general_ledger as allotment_account_title,
                ors_account.uacs as ors_uacs,
                ors_account.general_ledger as ors_account_title,
                process_ors_entries.amount,
                process_ors_entries.reporting_period,
                process_ors.is_cancelled



                FROM process_ors_entries
                LEFT JOIN process_ors ON process_ors_entries.process_ors_id = process_ors.id
                LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
                LEFT JOIN payee ON `transaction`.payee_id = payee.id
                LEFT JOIN record_allotment_entries ON process_ors_entries.record_allotment_entries_id = record_allotment_entries.id
                LEFT JOIN chart_of_accounts as ors_account ON process_ors_entries.chart_of_account_id = ors_account.id
                LEFT JOIN chart_of_accounts as allotment_account ON record_allotment_entries.chart_of_account_id = allotment_account.id

                WHERE
                process_ors.type = 'ors'
                ORDER BY process_ors.created_at DESC
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS process_ors_new_view")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210921_095131_create_process_ors_view_new cannot be reverted.\n";

        return false;
    }
    */
}
