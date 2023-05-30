<?php

use yii\db\Migration;

/**
 * Class m230525_021727_update_dv_aucs_index_view
 */
class m230525_021727_update_dv_aucs_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS dv_aucs_index;
            CREATE VIEW dv_aucs_index as 
                WITH dvItemTtl as (
                SELECT 
                dv_aucs_entries.dv_aucs_id,
                SUM(dv_aucs_entries.amount_disbursed) as ttlAmtDisbursed,
                SUM(dv_aucs_entries.vat_nonvat +
                dv_aucs_entries.ewt_goods_services + 
                dv_aucs_entries.compensation) as ttlTax
                FROM dv_aucs_entries 
                WHERE 
                dv_aucs_entries.is_deleted = 0
                GROUP BY
                dv_aucs_entries.dv_aucs_id
                ORDER BY dv_aucs_entries.id DESC
                ),
                dvOrs as (
                SELECT 
                orss.dv_aucs_id,
                GROUP_CONCAT(orss.ors_num) as orsNums
                FROM 
                (
                SELECT 
                dv_aucs_entries.dv_aucs_id,
                process_ors.serial_number as ors_num
                FROM dv_aucs_entries
                JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id
                WHERE dv_aucs_entries.is_deleted = 0 
                GROUP BY 
                dv_aucs_entries.dv_aucs_id,
                dv_aucs_entries.process_ors_id
                
                ) as orss
                GROUP BY
                orss.dv_aucs_id
                ORDER BY orss.dv_aucs_id DESC
                )
                SELECT 
                dv_aucs.id,
                dv_aucs.dv_number,
                dv_aucs.reporting_period,
                
                dv_aucs.particular,
                nature_of_transaction.`name` as natureOfTxn,
                mrd_classification.`name` as mrdName,
                
                payee.account_name as payee,
                UPPER(banks.`name`) as bank_name,
                payee.account_num,
                books.`name` as book_name,
                dvItemTtl.ttlAmtDisbursed,
                dvItemTtl.ttlTax,
                dvItemTtl.ttlAmtDisbursed+
                dvItemTtl.ttlTax as grossAmt,
                dvOrs.orsNums,
                dv_aucs.is_cancelled,
                IFNULL(dv_transaction_type.`name`,dv_aucs.transaction_type) as txnType
                FROM
                dv_aucs
                LEFT JOIN payee ON dv_aucs.payee_id = payee.id
                LEFT JOIN books ON dv_aucs.book_id = books.id
                JOIN dvItemTtl ON dv_aucs.id = dvItemTtl.dv_aucs_id
                LEFT JOIN dvOrs ON dv_aucs.id = dvOrs.dv_aucs_id
                LEFT JOIN mrd_classification ON dv_aucs.mrd_classification_id = mrd_classification.id
                LEFT JOIN nature_of_transaction ON dv_aucs.nature_of_transaction_id = nature_of_transaction.id
                LEFT JOIN dv_transaction_type ON dv_aucs.fk_dv_transaction_type_id = dv_transaction_type.id 
                LEFT JOIN banks ON payee.fk_bank_id = banks.id
               
                 
            
         SQL;
        $this->execute($sql);
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
        echo "m230525_021727_update_dv_aucs_index_view cannot be reverted.\n";

        return false;
    }
    */
}
