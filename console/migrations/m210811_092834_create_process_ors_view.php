<?php

use yii\db\Migration;

/**
 * Class m210811_092834_create_process_ors_view
 */
class m210811_092834_create_process_ors_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS process_ors_view;
            CREATE VIEW process_ors_view as
            SELECT 
            raouds.id,
            process_ors.serial_number,
            process_ors.reporting_period,
            `transaction`.tracking_number,
            `transaction`.particular,
            payee.account_name,
            record_allotment_for_ors.uacs as allotment_uacs,
            record_allotment_for_ors.general_ledger as allotment_general_ledger,
            chart_of_accounts.uacs as ors_uacs,
            chart_of_accounts.general_ledger as ors_general_ledger,
            raoud_entries.amount

            FROM `process_ors`
            INNER JOIN raouds ON process_ors.id = raouds.process_ors_id
            LEFT JOIN raoud_entries ON raouds.id = raoud_entries.raoud_id
            INNER JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
            INNER JOIN payee on `transaction`.payee_id = payee.id
            INNER JOIN record_allotment_for_ors ON raouds.record_allotment_entries_id = record_allotment_for_ors.id
            INNER JOIN chart_of_accounts ON raoud_entries.chart_of_account_id = chart_of_accounts.id

            ORDER BY raouds.id DESC




        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW IF EXISTS process_ors_view')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210811_092834_create_process_ors_view cannot be reverted.\n";

        return false;
    }
    */
}
