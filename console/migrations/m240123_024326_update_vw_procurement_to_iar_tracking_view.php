<?php

use yii\db\Migration;

/**
 * Class m240123_024326_update_vw_procurement_to_iar_tracking_view
 */
class m240123_024326_update_vw_procurement_to_iar_tracking_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql  = <<<SQL
                    DROP VIEW IF EXISTS vw_procurement_to_iar_tracking;
                    CREATE VIEW vw_procurement_to_iar_tracking as 
                            WITH cte_rfqDetails as (SELECT 
                            pr_rfq.id as rfq_id,
                            pr_rfq.mooe_amount as abc_mooe_amount,
                            pr_rfq.co_amount as abc_co_amount,
                            pr_rfq.rfq_number,
                            pr_rfq._date as rfq_date,
                            pr_rfq.deadline as rfq_deadline,
                            pr_rfq.source_of_fund,
                            (CASE 
                                WHEN pr_rfq.is_cancelled = 1 THEN 'Cancelled'
                                ELSE 'Good'
                            END) as rfq_is_cancelled,
                            pr_rfq_item.pr_purchase_request_item_id,
                            pr_rfq_item.id as rfq_item_id,
                            mfos.rfq_mfos
                            FROM pr_rfq
                            JOIN pr_rfq_item ON pr_rfq.id = pr_rfq_item.pr_rfq_id
                            LEFT JOIN (SELECT 
                                tbl_rfq_mfos.fk_rfq_id,
                                GROUP_CONCAT(CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`))  as rfq_mfos
                                FROM tbl_rfq_mfos
                                JOIN mfo_pap_code ON tbl_rfq_mfos.fk_mfo_pap_code_id = mfo_pap_code.id
                                GROUP BY tbl_rfq_mfos.fk_rfq_id) as mfos ON pr_rfq.id =mfos.fk_rfq_id
                            ),       
                            cte_aoqToPoDetails as (SELECT 
                            pr_aoq.aoq_number,
                            (CASE 
                                WHEN pr_aoq.is_cancelled = 1 THEN 'Cancelled'
                                ELSE 'Good'
                            END) as aoq_is_cancelled,
                            payee.registered_name as payee_name,
                            pr_aoq_entries.amount as bidAmount,
                            pr_purchase_order_item.serial_number as po_number,
                            (CASE 
                                WHEN pr_purchase_order.is_cancelled  = 1 OR pr_purchase_order_item.is_cancelled = 1 THEN 'Cancelled'
                                ELSE 'Good'
                            END) as po_is_cancelled,
                            pr_aoq_entries.pr_rfq_item_id,
                            pr_aoq_entries.id,
                            pr_purchase_order_items_aoq_items.id as po_aoq_item_id,
                            poTransmittal.poTransmittalNumber,
                            poTransmittal.poTransmittalDate,
                            pr_purchase_order.id as purchase_order_id,
                            pr_aoq.id as aoq_id,
                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.pre_proc_conference
                                ELSE 'N/A'
                            END 
                            ) as pre_proc_conference,
                            pr_purchase_order.philgeps_reference_num,
                   
                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.post_of_ib
                                ELSE 'N/A'
                            END 
                            ) as post_of_ib,

                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.actual_proc_pre_bid_conf
                                ELSE 'N/A'
                            END 
                            ) as actual_proc_pre_bid_conf,
                            (CASE 
                                WHEN pr_mode_of_procurement.is_bidding= 1 THEN pr_purchase_order.actual_proc_eligibility_check
                                WHEN with_postponement.to_date IS NOT NULL THEN with_postponement.to_date
                                ELSE pr_rfq.deadline
                            END 
                            ) as actual_proc_eligibility_check,
                            (CASE 
                                WHEN pr_mode_of_procurement.is_bidding=1 THEN pr_purchase_order.actual_proc_opening_of_bids
                                WHEN with_postponement.to_date IS NOT NULL THEN with_postponement.to_date
                                ELSE pr_rfq.deadline
                            END 
                            ) as actual_proc_opening_of_bids,
                            (CASE 
                                WHEN pr_mode_of_procurement.is_bidding= 1 THEN pr_purchase_order.actual_proc_bid_evaluation
                                WHEN with_postponement.to_date IS NOT NULL THEN with_postponement.to_date
                                ELSE pr_rfq.deadline
                            END 
                            ) as actual_proc_bid_evaluation,
                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.actual_proc_post_qual
                                ELSE 'N/A'
                            END 
                            ) as actual_proc_post_qual,
                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.notice_of_award
                                ELSE pr_purchase_order.po_date
                            END 
                            ) as notice_of_award,
                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.contract_signing
                                ELSE pr_purchase_order.po_date
                            END 
                            ) as contract_signing,
                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.notice_to_proceed
                                ELSE 'N/A'
                            END 
                            ) as notice_to_proceed,
                            pr_purchase_order.bac_resolution_award,
                            pr_purchase_order.mooe_amount as contract_mooe_amount,
                            pr_purchase_order.co_amount as contract_co_amount,
                            pr_purchase_order.po_date,
                            pr_mode_of_procurement.mode_name as mode_of_procurement_name,
                                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.invitation_pre_bid_conf
                                ELSE 'N/A'
                            END 
                            ) as invitation_pre_bid_conf,
                                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.invitation_eligibility_check
                                ELSE 'N/A'
                            END 
                            ) as invitation_eligibility_check,
                                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.invitation_opening_of_bids
                                ELSE 'N/A'
                            END 
                            ) as invitation_opening_of_bids,
                                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.invitation_bid_evaluation
                                ELSE 'N/A'
                            END 
                            ) as invitation_bid_evaluation,
                                            (CASE pr_mode_of_procurement.is_bidding
                                WHEN 1 THEN pr_purchase_order.invitation_post_qual
                                ELSE 'N/A'
                            END 
                            ) as invitation_post_qual
                        FROM 
                        pr_aoq
                        JOIN pr_aoq_entries ON pr_aoq.id = pr_aoq_entries.pr_aoq_id
                        LEFT JOIN pr_purchase_order_items_aoq_items ON pr_aoq_entries.id = pr_purchase_order_items_aoq_items.fk_aoq_entries_id
                        LEFT JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
                        LEFT JOIN pr_purchase_order ON pr_purchase_order_item.fk_pr_purchase_order_id = pr_purchase_order.id
                        LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                        LEFT JOIN pr_rfq ON pr_aoq.pr_rfq_id = pr_rfq.id
                        LEFT JOIN pr_mode_of_procurement ON pr_rfq.fk_mode_of_procurement_id = pr_mode_of_procurement.id
                        LEFT JOIN (SELECT notice_of_postponement_items.*,notice_of_postponement.to_date FROM `notice_of_postponement_items`
                            JOIN notice_of_postponement ON notice_of_postponement_items.fk_notice_of_postponement_id = notice_of_postponement.id
                            WHERE is_deleted = 0
                            AND notice_of_postponement.is_final = 1
                                ) as with_postponement ON pr_rfq.id = with_postponement.fk_rfq_id
                        LEFT JOIN (SELECT 
                        purchase_order_transmittal.serial_number as poTransmittalNumber,
                        purchase_order_transmittal.`date` as poTransmittalDate,
                        purchase_order_transmittal_items.fk_purchase_order_item_id
                        FROM purchase_order_transmittal 
                        LEFT JOIN purchase_order_transmittal_items ON purchase_order_transmittal.id = purchase_order_transmittal_items.fk_purchase_order_transmittal_id
                        ) as poTransmittal ON pr_purchase_order_item.id = poTransmittal.fk_purchase_order_item_id
                        WHERE 
                        pr_aoq_entries.is_deleted = 0
                        AND pr_aoq_entries.is_lowest = 1),
                        cte_rfiToIarDetails as (
                        SELECT 
                            request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id,
                            request_for_inspection.rfi_number,
                            request_for_inspection.`date` as rfi_date,
                            request_for_inspection_items.`from`,
                            request_for_inspection_items.`to`,
                            request_for_inspection_items.quantity,
                            inspection_report.ir_number,
                            iar.iar_number,
                            iarTransmittal.iarTransmittalNumber,
                            iarTransmittal.iarTransmittalDate,
                            request_for_inspection.id as request_for_inspection_id,
                            inspection_report.id as inspection_report_id,
                            iar.id as iar_id,
                            iar_ro_transactions.ro_transaction_numbers
                            FROM 
                            request_for_inspection
                            JOIN request_for_inspection_items ON request_for_inspection.id = request_for_inspection_items.fk_request_for_inspection_id
                            LEFT JOIN inspection_report_items ON request_for_inspection_items.id = inspection_report_items.fk_request_for_inspection_item_id
                            LEFT JOIN inspection_report ON inspection_report_items.fk_inspection_report_id = inspection_report.id
                            LEFT JOIN iar ON inspection_report.id = iar.fk_ir_id
                            LEFT JOIN (SELECT 
                            iar.id,
                            GROUP_CONCAT(`transaction`.tracking_number ORDER BY `transaction`.tracking_number SEPARATOR ', ') AS ro_transaction_numbers
                            FROM 
                            iar
                            LEFT JOIN transaction_iars ON `iar`.id  = transaction_iars.fk_iar_id 
                            LEFT JOIN `transaction` ON transaction_iars.fk_transaction_id = `transaction`.id
                            WHERE transaction_iars.is_deleted = 0
                            GROUP BY iar.id
                            ) as iar_ro_transactions ON iar.id = iar_ro_transactions.id
                            LEFT JOIN (SELECT 
                            iar_transmittal.serial_number as iarTransmittalNumber,
                            iar_transmittal.`date` as iarTransmittalDate,
                            iar_transmittal_items.fk_iar_id
                            FROM iar_transmittal_items 
                            LEFT JOIN iar_transmittal ON iar_transmittal_items.fk_iar_transmittal_id = iar_transmittal.id
                            WHERE iar_transmittal_items.is_deleted = 0) as iarTransmittal ON iar.id = iarTransmittal.fk_iar_id
                            WHERE request_for_inspection_items.is_deleted = 0
                            )
                        SELECT 
                        office.office_name,
                        divisions.division,
                        pr_purchase_request.pr_number,
                        pr_purchase_request.purpose,
                        pr_purchase_request.`date` as pr_date,
                        pr_stock.stock_title as stock_name,
                        REPLACE(REPLACE(pr_purchase_request_item.specification,'[n]',' '),'<br>',' ')as specification,
                        (CASE
                            WHEN pr_purchase_request.is_cancelled  =1 THEN 'Cancelled'
                            ELSE 'Good'
                        END)as pr_is_cancelled,
                        pr_purchase_request_item.quantity,
                        pr_purchase_request_item.unit_cost,
                        cte_rfqDetails.rfq_id,
                        cte_rfqDetails.source_of_fund,
                        cte_rfqDetails.rfq_number,
                        cte_rfqDetails.rfq_date,
                        cte_rfqDetails.rfq_deadline,
                        cte_rfqDetails.rfq_is_cancelled,
                        cte_rfqDetails.rfq_mfos,
                        cte_aoqToPoDetails.aoq_number,
                        cte_aoqToPoDetails.aoq_is_cancelled,
                        cte_aoqToPoDetails.payee_name,
                        cte_aoqToPoDetails.bidAmount,
                        pr_purchase_request_item.quantity * cte_aoqToPoDetails.bidAmount as bidGrossAmount,
                        cte_aoqToPoDetails.po_number,
                        cte_aoqToPoDetails.po_is_cancelled,
                        cte_aoqToPoDetails.poTransmittalNumber,
                        cte_aoqToPoDetails.poTransmittalDate,
                        cte_rfiToIarDetails.rfi_number,
                        cte_rfiToIarDetails.`rfi_date` ,
                        cte_rfiToIarDetails.`from` as  inspection_from,
                        cte_rfiToIarDetails.`to` as inspection_to,
                        cte_rfiToIarDetails.quantity as inspected_quantity,
                        cte_rfiToIarDetails.ir_number,
                        cte_rfiToIarDetails.iar_number,
                        cte_rfiToIarDetails.iarTransmittalNumber,
                        cte_rfiToIarDetails.iarTransmittalDate,
                        cte_rfiToIarDetails.request_for_inspection_id,
                        cte_rfiToIarDetails.inspection_report_id,
                        cte_rfiToIarDetails.iar_id,
                        cte_rfiToIarDetails.ro_transaction_numbers ,
                        cte_aoqToPoDetails.purchase_order_id,
                        cte_aoqToPoDetails.aoq_id,
                        pr_purchase_request.id as purchase_request_id,
                        cte_aoqToPoDetails.pre_proc_conference,
                        cte_aoqToPoDetails.philgeps_reference_num,
                        cte_aoqToPoDetails.post_of_ib,
                        cte_aoqToPoDetails.actual_proc_pre_bid_conf,
                        cte_aoqToPoDetails.actual_proc_eligibility_check,
                        cte_aoqToPoDetails.actual_proc_opening_of_bids,
                        cte_aoqToPoDetails.actual_proc_bid_evaluation,
                        cte_aoqToPoDetails.actual_proc_post_qual,
                        cte_aoqToPoDetails.invitation_pre_bid_conf,
                        cte_aoqToPoDetails.invitation_eligibility_check,
                        cte_aoqToPoDetails.invitation_opening_of_bids,
                        cte_aoqToPoDetails.invitation_bid_evaluation,
                        cte_aoqToPoDetails.invitation_post_qual,
                        cte_aoqToPoDetails.notice_of_award,
                        cte_aoqToPoDetails.contract_signing,
                        cte_aoqToPoDetails.notice_to_proceed,
                        cte_aoqToPoDetails.contract_mooe_amount,
                        cte_aoqToPoDetails.contract_co_amount,
                        cte_aoqToPoDetails.mode_of_procurement_name,
                        cte_aoqToPoDetails.po_date,
                        cte_rfqDetails.abc_mooe_amount,
                        cte_rfqDetails.abc_co_amount
                        FROM pr_purchase_request
                        JOIN pr_purchase_request_item ON pr_purchase_request.id = pr_purchase_request_item.pr_purchase_request_id
                        LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
                        LEFT JOIN cte_rfqDetails ON pr_purchase_request_item.id = cte_rfqDetails.pr_purchase_request_item_id
                        LEFT JOIN cte_aoqToPoDetails ON cte_rfqDetails.rfq_item_id = cte_aoqToPoDetails.pr_rfq_item_id
                        LEFT JOIN cte_rfiToIarDetails ON cte_aoqToPoDetails.po_aoq_item_id = cte_rfiToIarDetails.fk_pr_purchase_order_items_aoq_item_id
                        LEFT JOIN office ON  pr_purchase_request.fk_office_id = office.id
                        LEFT JOIN divisions ON pr_purchase_request.fk_division_id = divisions.id
                        WHERE 
                        pr_purchase_request_item.is_deleted  = 0
                        ORDER BY pr_purchase_request.pr_number 
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
        echo "m240123_024326_update_vw_procurement_to_iar_tracking_view cannot be reverted.\n";

        return false;
    }
    */
}
