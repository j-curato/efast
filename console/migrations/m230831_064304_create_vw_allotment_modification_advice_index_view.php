<?php

use yii\db\Migration;

/**
 * Class m230831_064304_create_vw_allotment_modification_advice_index_view
 */
class m230831_064304_create_vw_allotment_modification_advice_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $sql = <<< SQL
        //     DROP VIEW IF EXISTS vw_allotment_modification_advice_index;
        //     CREATE VIEW vw_allotment_modification_advice_index as 
        //         SELECT
        //         allotment_modification_advice.id,
        //         allotment_modification_advice.serial_num,
        //         allotment_modification_advice.`date`,
        //         allotment_modification_advice.particulars,
        //         allotment_modification_advice.reporting_period,
        //         office.office_name,
        //         divisions.division,
        //         books.`name` as book_name,
        //         allotment_type.type as allotment_type_name,
        //         mfo_pap_code.`name` as mfo_name,
        //         mfo_pap_code.`code` as mfo_code,
        //         document_recieve.`name` as document_receive_name,
        //         fund_source.`name` as fund_source_name
        //         FROM 
        //         allotment_modification_advice
        //         LEFT JOIN office ON allotment_modification_advice.fk_office_id = office.id
        //         LEFT JOIN divisions ON allotment_modification_advice.fk_division_id = divisions.id
        //         LEFT JOIN allotment_type ON allotment_modification_advice.fk_allotment_type_id = allotment_type.id
        //         LEFT JOIN mfo_pap_code ON allotment_modification_advice.fk_mfo_pap_id  = mfo_pap_code.id
        //         LEFT JOIN document_recieve ON allotment_modification_advice.fk_document_receive_id = document_recieve.id
        //         LEFT JOIN fund_source ON allotment_modification_advice.fk_fund_source = fund_source.id
        //         LEFT JOIN books ON allotment_modification_advice.fk_book_id  = books.id
        // SQL;
        // $this->execute($sql);
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
        echo "m230831_064304_create_vw_allotment_modification_advice_index_view cannot be reverted.\n";

        return false;
    }
    */
}
