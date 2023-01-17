<?php

use yii\db\Migration;

/**
 * Class m230117_081827_update_record_allotments_view
 */
class m230117_081827_update_record_allotments_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS record_allotments_view;
CREATE VIEW record_allotments_view as SELECT 
record_allotments.id,
record_allotment_entries.id as entry_id,
record_allotments.reporting_period,
record_allotments.serial_number,
record_allotments.date_issued,
record_allotments.valid_until,
record_allotments.particulars,
document_recieve.`name` as document_recieve,
fund_cluster_code.`name` as fund_cluster_code,
financing_source_code.`name` as financing_source_code,
fund_category_and_classification_code.`name` as fund_classification,
authorization_code.`name` as authorization_code,
mfo_pap_code.`code` as mfo_code,
mfo_pap_code.`name` as mfo_name,
responsibility_center.`name` as responsibility_center,
fund_source.`name` as fund_source,
chart_of_accounts.uacs,
chart_of_accounts.general_ledger,
major_accounts.`name` as allotment_class,
record_allotment_entries.amount,
IF(document_recieve.`name`='GARO','NCA','NTA') as nca_nta,
IF(mfo_pap_code.`name`='CARP','CARP','101') as carp_101,
        IFNULL(total_ors.total_ors,0) as total_ors,
record_allotment_entries.amount -  IFNULL(total_ors.total_ors,0) as balance,
books.`name` as book,
    office.office_name,
    divisions.division,
    allotment_type.type as allotment_type,
    total_pr_allotment.ttl_pr_amount

FROM record_allotments
LEFT JOIN record_allotment_entries ON record_allotments.id = record_allotment_entries.record_allotment_id
LEFT JOIN responsibility_center ON record_allotments.responsibility_center_id = responsibility_center.id
LEFT JOIN fund_cluster_code ON record_allotments.fund_cluster_code_id  = fund_cluster_code.id
LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id
LEFT JOIN financing_source_code  ON record_allotments.financing_source_code_id = financing_source_code.id
LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id  = mfo_pap_code.id
LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
LEFT JOIN fund_category_and_classification_code ON record_allotments.fund_category_and_classification_code_id  = fund_category_and_classification_code.id
LEFT JOIN books ON record_allotments.book_id = books.id
LEFT JOIN authorization_code ON record_allotments.authorization_code_id = authorization_code.id
LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id 
    LEFT JOIN office ON record_allotments.office_id = office.id
    LEFT JOIN divisions ON record_allotments.division_id  = divisions.id
    LEFT JOIN allotment_type ON record_allotments.allotment_type_id  = allotment_type.id
    LEFT JOIN (
        SELECT 
            process_ors_entries.record_allotment_entries_id,
            SUM(process_ors_entries.amount) as total_ors
            FROM process_ors
            INNER JOIN process_ors_entries ON  process_ors.id =process_ors_entries.process_ors_id 
            WHERE
            process_ors.is_cancelled = 0

            GROUP BY
            process_ors_entries.record_allotment_entries_id
            ) as total_ors ON record_allotment_entries.id = total_ors.record_allotment_entries_id
    LEFT JOIN (SELECT
                        pr_purchase_request_allotments.fk_record_allotment_entries_id,
                        SUM(pr_purchase_request_allotments.amount) as ttl_pr_amount
                        FROM pr_purchase_request_allotments
                        WHERE pr_purchase_request_allotments.is_deleted = 0
                        GROUP BY pr_purchase_request_allotments.fk_record_allotment_entries_id
                        ) as total_pr_allotment ON record_allotment_entries.id = total_pr_allotment.fk_record_allotment_entries_id 

ORDER BY record_allotments.reporting_period DESC ")
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
        echo "m230117_081827_update_record_allotments_view cannot be reverted.\n";

        return false;
    }
    */
}
