<?php

use yii\db\Migration;

/**
 * Class m230203_062212_create_record_allotment_detailed_view
 */
class m230203_062212_create_record_allotment_detailed_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS record_allotment_detailed;
        CREATE VIEW record_allotment_detailed as WITH  detailedUsedAllotments as (
            SELECT 
                pr_purchase_request_allotments.fk_record_allotment_entries_id as allotment_entry_id,
                0 as orsAmt,
                pr_purchase_request_allotments.amount as prAmt,
                0 as trAmt
            FROM  pr_purchase_request_allotments 
            WHERE pr_purchase_request_allotments.is_deleted = 0
        
        UNION ALL 
        
            SELECT 
                transaction_items.fk_record_allotment_entries_id as allotment_entry_id,
                0 as orsAmt,
                0 as prAmt,
                transaction_items.amount as trAmt
            FROM 
             transaction_items 
            WHERE transaction_items.is_deleted = 0
        
        UNION ALL 
        
            SELECT 
                pr_purchase_request_allotments.fk_record_allotment_entries_id as allotment_entry_id,
                0 as orsAmt,
                transaction_pr_items.amount*-1 as prAmt,
                0 as trAmt
            FROM transaction_pr_items
            INNER JOIN pr_purchase_request_allotments ON transaction_pr_items.fk_pr_allotment_id = pr_purchase_request_allotments.id
            WHERE transaction_pr_items.is_deleted = 0
        UNION ALL 
        
            SELECT 
                process_ors_entries.record_allotment_entries_id as allotment_entry_id,
                process_ors_entries.amount as orsAmt,
                0 as prAmt,
                0 trAmt
            FROM process_ors_entries
        UNION ALL 
            SELECT 
                transaction_items.fk_record_allotment_entries_id as allotment_entry_id,
                0 as orsAmt,
                0 as prAmt,
                process_ors_txn_items.amount*-1 trAmt
            FROM 
                process_ors_txn_items
            LEFT JOIN transaction_items ON process_ors_txn_items.fk_transaction_item_id = transaction_items.id
            WHERE process_ors_txn_items.is_deleted = 0
        
        ),
        consoUsedAllotments as (
        
            SELECT 
                detailedUsedAllotments.allotment_entry_id,
                SUM(detailedUsedAllotments.orsAmt) as ttlOrsAmt,
                SUM(prAmt) as ttlPrAmt,
                SUM(trAmt) as ttlTrAmt
            FROM detailedUsedAllotments
            GROUP BY detailedUsedAllotments.allotment_entry_id
        
        )
        SELECT 
        record_allotments.id,
        record_allotment_entries.id as allotment_entry_id,
        record_allotments.serial_number as allotmentNumber,
        DATE_FORMAT(CONCAT(record_allotments.reporting_period,'-01'), '%Y') as budget_year,
        UPPER(office.office_name) as office_name,
        UPPER(divisions.division) as division ,
        CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
        fund_source.`name` as fund_source_name,
        chart_of_accounts.general_ledger as account_title,
        record_allotment_entries.amount,
        books.`name` as book_name,
        consoUsedAllotments.ttlOrsAmt,
        consoUsedAllotments.ttlPrAmt,
        consoUsedAllotments.ttlTrAmt,
        IFNULL(record_allotment_entries.amount,0)-
        IFNULL(consoUsedAllotments.ttlOrsAmt,0)-
        IFNULL(consoUsedAllotments.ttlPrAmt,0)-
        IFNULL(consoUsedAllotments.ttlTrAmt,0) as balance,
        IFNULL(record_allotment_entries.amount,0) -  IFNULL(consoUsedAllotments.ttlOrsAmt,0) as balAfterObligation,
        record_allotments.reporting_period,
        record_allotments.date_issued,
        record_allotments.valid_until,
        record_allotments.particulars,
        document_recieve.`name` as document_recieve,
        fund_cluster_code.`name` as fund_cluster_code,
        financing_source_code.`name` as financing_source_code,
        fund_category_and_classification_code.`name` as fund_classification,
        authorization_code.`name` as authorization_code,
        responsibility_center.`name` as responsibility_center,
        major_accounts.`name` as allotment_class,
        IF(document_recieve.`name`='GARO','NCA','NTA') as nca_nta,
        IF(mfo_pap_code.`name`='CARP','CARP','101') as carp_101,
        books.`name` as book,
        allotment_type.type as allotment_type,
        chart_of_accounts.id as chart_of_account_id,
        chart_of_accounts.uacs,
        mfo_pap_code.`code` as mfo_code
        
        FROM record_allotment_entries 
        INNER JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
        LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
        LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
        LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
        LEFT JOIN office ON record_allotments.office_id = office.id
        lEFT JOIN divisions ON record_allotments.division_id = divisions.id
        LEFT JOIN books ON record_allotments.book_id = books.id
        LEFT JOIN consoUsedAllotments ON record_allotment_entries.id = consoUsedAllotments.allotment_entry_id
        LEFT JOIN responsibility_center ON record_allotments.responsibility_center_id = responsibility_center.id
        LEFT JOIN fund_cluster_code ON record_allotments.fund_cluster_code_id  = fund_cluster_code.id
        LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id
        LEFT JOIN financing_source_code  ON record_allotments.financing_source_code_id = financing_source_code.id
        LEFT JOIN fund_category_and_classification_code ON record_allotments.fund_category_and_classification_code_id  = fund_category_and_classification_code.id
        LEFT JOIN authorization_code ON record_allotments.authorization_code_id = authorization_code.id
        LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id 
        LEFT JOIN allotment_type ON record_allotments.allotment_type_id  = allotment_type.id
        ")
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
        echo "m230203_062212_create_record_allotment_detailed_view cannot be reverted.\n";

        return false;
    }
    */
}
