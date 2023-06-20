<?php

use yii\db\Migration;

/**
 * Class m230620_055716_update_vw_cash_received_view
 */
class m230620_055716_update_vw_cash_received_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_cash_received;
        CREATE VIEW vw_cash_received as SELECT 
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
        CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
        ttlInAcic.ttl,
        cash_received.amount - COALESCE(ttlInAcic.ttl,0) as balance

                FROM cash_received
        LEFT JOIN (SELECT 
        acic_cash_receive_items.fk_cash_receive_id,
        SUM(acic_cash_receive_items.amount) as ttl
        FROM acic_cash_receive_items
        WHERE acic_cash_receive_items.is_deleted = 0
        GROUP BY
        acic_cash_receive_items.fk_cash_receive_id) as ttlInAcic ON cash_received.id = ttlInAcic.fk_cash_receive_id
        LEFT JOIN document_recieve ON cash_received.document_recieved_id = document_recieve.id
        LEFT JOIN books ON cash_received.book_id = books.id
        LEFT JOIN mfo_pap_code ON cash_received.mfo_pap_code_id = mfo_pap_code.id ")
            ->query();
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
        echo "m230620_055716_update_vw_cash_received_view cannot be reverted.\n";

        return false;
    }
    */
}
