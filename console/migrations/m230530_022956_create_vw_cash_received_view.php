<?php

use yii\db\Migration;

/**
 * Class m230530_022956_create_vw_cash_received_view
 */
class m230530_022956_create_vw_cash_received_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_cash_received;
                CREATE VIEW vw_cash_received AS SELECT 
                cash_received.id,
                cash_received.date,
                cash_received.reporting_period,
                cash_received.valid_from,
                cash_received.valid_to,
                cash_received.purpose,
                cash_received.amount,
                cash_received.nca_no,
                cash_received.nta_no,
                document_recieve.`name` as document_receive_name,
                books.`name` as book_name,
                CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name

                FROM cash_received
                LEFT JOIN document_recieve ON cash_received.document_recieved_id = document_recieve.id
                LEFT JOIN books ON cash_received.book_id = books.id
                LEFT JOIN mfo_pap_code ON cash_received.mfo_pap_code_id = mfo_pap_code.id")
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
        echo "m230530_022956_create_vw_cash_received_view cannot be reverted.\n";

        return false;
    }
    */
}
