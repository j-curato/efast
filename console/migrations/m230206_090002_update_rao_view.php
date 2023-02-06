<?php

use yii\db\Migration;

/**
 * Class m230206_090002_update_rao_view
 */
class m230206_090002_update_rao_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand("DROP VIEW IF EXISTS rao;
CREATE VIEW rao as SELECT
document_recieve.`name` as document_name,
fund_cluster_code.`name` as fund_cluster_code_name,
CONCAT(financing_source_code.`name`,'-',financing_source_code.`description`) as financing_source_code_name,
fund_category_and_classification_code.`name` as fund_category_and_classification_code_name,
authorization_code.`name` as authorization_code_name,
mfo_pap_code.`name` as mfo_pap_code_name,
fund_source.`name` as fund_source_name,
process_ors_entries.reporting_period,
chart_of_accounts.uacs,
chart_of_accounts.general_ledger,
books.`name` as book_name,
process_ors_entries.amount as ors_amount,
0 as allotment_amount,
divisions.division,
office.office_name,
    record_allotments.serial_number as allotment_number,
    process_ors.serial_number as ors_number,
payee.account_name as payee,
`transaction`.particular ,
process_ors.is_cancelled

FROM process_ors_entries
    LEFT JOIN process_ors ON process_ors_entries.process_ors_id = process_ors.id
    LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
LEFT JOIN payee ON `transaction`.payee_id = payee.id

LEFT JOIN record_allotment_entries ON process_ors_entries.record_allotment_entries_id = record_allotment_entries.id
LEFT JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
LEFT JOIN chart_of_accounts ON   process_ors_entries.chart_of_account_id = chart_of_accounts.id
LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id
LEFT JOIN fund_cluster_code ON record_allotments.fund_cluster_code_id = fund_cluster_code.id
LEFT JOIN financing_source_code ON record_allotments.financing_source_code_id = financing_source_code.id
LEFT JOIN fund_category_and_classification_code ON record_allotments.fund_category_and_classification_code_id = fund_category_and_classification_code.id
LEFT JOIN authorization_code ON record_allotments.authorization_code_id = authorization_code.id
LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
LEFT JOIN books ON record_allotments.book_id = books.id
LEFT JOIN divisions ON record_allotments.division_id = divisions.id
LEFT JOIN office ON record_allotments.office_id = office.id

UNION ALL

SELECT 

CONVERT(document_recieve.`name` USING utf8) as document_name,
CONVERT( fund_cluster_code.`name`USING utf8) as fund_cluster_code_name,
CONCAT(financing_source_code.`name`,'-',financing_source_code.`description`)  as financing_source_code_name,
CONVERT(fund_category_and_classification_code.`name`USING utf8) as fund_category_and_classification_code_name,
CONVERT(authorization_code.`name`USING utf8) as authorization_code_name,
CONVERT(mfo_pap_code.`name`USING utf8) as mfo_pap_code_name,
CONVERT(fund_source.`name`USING utf8) as fund_source_name,
CONVERT(record_allotments.reporting_period USING utf8) as reporting_period,
CONVERT(chart_of_accounts.uacs USING utf8) as uacs,
CONVERT(chart_of_accounts.general_ledger USING utf8)as general_ledger,
CONVERT(books.`name` USING utf8) as book_name,
0 as ors_amount,
CONVERT(record_allotment_entries.amount USING utf8) as allotment_amount,
divisions.division,
office.office_name,
record_allotments.serial_number as allotment_number,
'' as ors_number,
'' as payee,
'' as particular ,
'' as is_cancelled

FROM record_allotment_entries
LEFT JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
LEFT JOIN chart_of_accounts ON   record_allotment_entries.chart_of_account_id = chart_of_accounts.id
LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id
LEFT JOIN fund_cluster_code ON record_allotments.fund_cluster_code_id = fund_cluster_code.id
LEFT JOIN financing_source_code ON record_allotments.financing_source_code_id = financing_source_code.id
LEFT JOIN fund_category_and_classification_code ON record_allotments.fund_category_and_classification_code_id = fund_category_and_classification_code.id
LEFT JOIN authorization_code ON record_allotments.authorization_code_id = authorization_code.id
LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
LEFT JOIN books ON record_allotments.book_id = books.id 
LEFT JOIN divisions ON record_allotments.division_id = divisions.id
LEFT JOIN office ON record_allotments.office_id = office.id ")
            ->execute();
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
        echo "m230206_090002_update_rao_view cannot be reverted.\n";

        return false;
    }
    */
}
