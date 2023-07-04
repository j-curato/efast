<?php

use yii\db\Migration;

/**
 * Class m210713_025940_create_advances_cash_disbursement_view
 */
class m210713_025940_create_advances_cash_disbursement_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("
        DROP VIEW IF EXISTS advances_cash_disbursement;
        CREATE VIEW advances_cash_disbursement as
       SELECT 
        cash_disbursement.id,
        books.`name` as book_name,
        cash_disbursement.mode_of_payment,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        cash_disbursement.issuance_date,
        dv_aucs.dv_number,
        payee.account_name as payee,
        dv_aucs.particular,
        q.total_amount_disbursed
   
        
        
        FROM 
        cash_disbursement
        INNER JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
        LEFT JOIN(
        SELECT SUM(dv_aucs_entries.amount_disbursed) as total_amount_disbursed,
        dv_aucs_entries.dv_aucs_id
         FROM dv_aucs_entries
         GROUP BY dv_aucs_entries.dv_aucs_id)
         as q ON dv_aucs.id = q.dv_aucs_id
        INNER JOIN payee ON dv_aucs.payee_id = payee.id
        INNER JOIN  books ON cash_disbursement.book_id = books.id
        INNER JOIN nature_of_transaction ON dv_aucs.nature_of_transaction_id = nature_of_transaction.id
        LEFT JOIN advances_entries ON cash_disbursement.id = advances_entries.cash_disbursement_id
        WHERE 
        advances_entries.id IS NULL
        AND
        cash_disbursement.is_cancelled=0
        AND nature_of_transaction.`name` = 'CA to SDOs/OPEX' 

				ORDER BY cash_disbursement.id DESC")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW advances_cash_disbursement')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210713_025940_create_advances_cash_disbursement_view cannot be reverted.\n";

        return false;
    }
    */
}
