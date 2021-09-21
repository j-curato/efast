<?php

use yii\db\Migration;

/**
 * Class m210921_063150_create_record_allotment_view
 */
class m210921_063150_create_record_allotment_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
             CREATE VIEW record_allotments_view as 
             SELECT 
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
                IF(mfo_pap_code.`name`='CARP','CARP','101') as carp_101



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
                LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id;
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS record_allotments_view")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210921_063150_create_record_allotment_view cannot be reverted.\n";

        return false;
    }
    */
}
