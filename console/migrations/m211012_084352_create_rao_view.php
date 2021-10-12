<?php

use yii\db\Migration;

/**
 * Class m211012_084352_create_rao_view
 */
class m211012_084352_create_rao_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP VIEW IF EXISTS rao;
        CREATE VIEW rao as 
            SELECT
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
            mfo_pap_code.division
            FROM process_ors_entries
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

            UNION ALL

            SELECT 
            document_recieve.`name` as document_name,
            fund_cluster_code.`name` as fund_cluster_code_name,
            CONCAT(financing_source_code.`name`,'-',financing_source_code.`description`) as financing_source_code_name,
            fund_category_and_classification_code.`name` as fund_category_and_classification_code_name,
            authorization_code.`name` as authorization_code_name,
            mfo_pap_code.`name` as mfo_pap_code_name,
            fund_source.`name` as fund_source_name,
            record_allotments.reporting_period,
            chart_of_accounts.uacs,
            chart_of_accounts.general_ledger,
            books.`name` as book_name,
            0 as ors_amount,
            record_allotment_entries.amount as allotment_amount,
            mfo_pap_code.division
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





        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS rao")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211012_084352_create_rao_view cannot be reverted.\n";

        return false;
    }
    */
}
