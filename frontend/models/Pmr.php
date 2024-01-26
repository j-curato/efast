<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "tbl_pmr".
 *
 * @property int $id
 * @property int $fk_office_id
 * @property string $reporting_period
 *
 * @property Office $fkOffice
 */
class Pmr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_pmr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_office_id', 'reporting_period'], 'required'],
            [['fk_office_id'], 'integer'],
            [['reporting_period'], 'string', 'max' => 255],
            [['fk_office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::class, 'targetAttribute' => ['fk_office_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_office_id' => ' Office ',
            'reporting_period' => 'Reporting Period',
        ];
    }

    /**
     * Gets query for [[FkOffice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function generatePmr($officeId, $reportingPeriod)
    {
    }
    public static function queryPmr($reportingPeriod, $officeId)
    {
        $period =  DateTime::createFromFormat("Y-m", $reportingPeriod);
        // Get the first day of the next month
        $firstDayOfNextMonth = date('Y-m-d', strtotime($reportingPeriod . '-01 +1 month'));

        // Get the last day of the current month (which is one day before the first day of the next month)
        $lastDayOfMonth = date('Y-m-d', strtotime($firstDayOfNextMonth . ' -1 day'));


        return Yii::$app->db->createCommand("WITH cte_rfq_purchase_orders as (
            SELECT 
            pr_purchase_order_item.serial_number,
            payee.registered_name,
            pr_aoq.pr_rfq_id,
            pr_purchase_order.po_date,
            pr_purchase_order.invitation_pre_bid_conf,
            pr_purchase_order.invitation_eligibility_check,
            pr_purchase_order.invitation_opening_of_bids,
            pr_purchase_order.invitation_bid_evaluation,
            pr_purchase_order.invitation_post_qual,
            (CASE pr_mode_of_procurement.is_bidding
            WHEN 1 THEN pr_purchase_order.notice_of_award
            ELSE CONCAT(pr_purchase_order_item.serial_number,':',pr_purchase_order.po_date)
            END 
            ) as notice_of_award,
            (CASE pr_mode_of_procurement.is_bidding
            WHEN 1 THEN pr_purchase_order.contract_signing
            ELSE CONCAT(pr_purchase_order_item.serial_number,':',pr_purchase_order.po_date)
            END 
            ) as contract_signing,
            (CASE pr_mode_of_procurement.is_bidding
            WHEN 1 THEN pr_purchase_order.notice_to_proceed
            ELSE NULL
            END 
            ) as notice_to_proceed
             FROM pr_purchase_order
            JOIN pr_purchase_order_item ON pr_purchase_order.id  = pr_purchase_order_item.fk_pr_purchase_order_id
            JOIN pr_purchase_order_items_aoq_items ON pr_purchase_order_item.id = pr_purchase_order_items_aoq_items.fk_purchase_order_item_id
            JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
            JOIN pr_aoq ON pr_aoq_entries.pr_aoq_id = pr_aoq.id
            JOIN pr_rfq ON pr_aoq.pr_rfq_id = pr_rfq.id
             LEFT JOIN pr_mode_of_procurement ON pr_rfq.fk_mode_of_procurement_id = pr_mode_of_procurement.id 
            LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
            WHERE 
            pr_purchase_order.is_cancelled = 0
            AND pr_purchase_order_item.is_cancelled = 0
            AND pr_aoq_entries.is_deleted = 0
            AND pr_aoq.is_cancelled = 0
            AND pr_aoq_entries.is_lowest = 1
            AND pr_purchase_order.po_date <= :lastDayOfMonth
            AND pr_purchase_order.po_date LIKE :yr
            GROUP BY 
            pr_purchase_order_item.serial_number,
            payee.registered_name,
            pr_aoq.pr_rfq_id,
            pr_purchase_order.po_date,
            pr_purchase_order.invitation_pre_bid_conf,
            pr_purchase_order.invitation_eligibility_check,
            pr_purchase_order.invitation_opening_of_bids,
            pr_purchase_order.invitation_bid_evaluation,
            pr_purchase_order.invitation_post_qual,
            (CASE pr_mode_of_procurement.is_bidding
            WHEN 1 THEN pr_purchase_order.notice_of_award
            ELSE CONCAT(pr_purchase_order_item.serial_number,':',pr_purchase_order.po_date)
            END 
            ),
            (CASE pr_mode_of_procurement.is_bidding
            WHEN 1 THEN pr_purchase_order.contract_signing
            ELSE CONCAT(pr_purchase_order_item.serial_number,':',pr_purchase_order.po_date)
            END 
            ) ,
            (CASE pr_mode_of_procurement.is_bidding
            WHEN 1 THEN pr_purchase_order.notice_to_proceed
            ELSE NULL
            END 
            ) 
            
            ), cte_rfq_iars as (SELECT 
            pr_rfq_item.pr_rfq_id,
            iar.iar_number,
            request_for_inspection_items.`from` as from_date,
            request_for_inspection_items.`to` as to_date
            FROM 
            request_for_inspection
            JOIN request_for_inspection_items ON request_for_inspection.id = request_for_inspection_items.fk_request_for_inspection_id
            JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
            JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
            JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
            LEFT JOIN inspection_report_items ON request_for_inspection_items.id = inspection_report_items.fk_request_for_inspection_item_id
            LEFT JOIN inspection_report ON inspection_report_items.fk_inspection_report_id = inspection_report.id
            LEFT JOIN iar ON inspection_report.id = iar.fk_ir_id
            WHERE 
             request_for_inspection_items.`from` <= :lastDayOfMonth
            AND request_for_inspection_items.`from` LIKE :yr
            GROUP BY
            pr_rfq_item.pr_rfq_id,
            iar.iar_number,
            request_for_inspection_items.`from`,
            request_for_inspection_items.`to` 
            )
            
            
            
            
            
            
            
            SELECT 
            pr_rfq.id,
            office.office_name,
            divisions.division as division_name,
            pr_purchase_request.purpose,
            pr_purchase_request.pr_number,
            pr_rfq.created_at as rfq_created_at,
            pr_rfq.rfq_number,
            COALESCE(pr_rfq.mooe_amount,0) as mooe_amount ,
            COALESCE(pr_rfq.co_amount,0) as co_amount ,
            pr_rfq.pre_proc_conference,
            pr_rfq.philgeps_reference_num,
            pr_rfq.pre_bid_conf,
            pr_rfq.post_qual,
            pr_rfq.post_of_ib,
            pr_rfq._date as rfq_date,
            pr_rfq.source_of_fund,
            COALESCE(contract_amount.contract_mooe_amount,0) as contract_mooe_amount ,
            COALESCE(contract_amount.contract_co_amount,0) as contract_co_amount ,
            pr_mode_of_procurement.mode_name as mode_of_procurement_name,
            (CASE 
                WHEN pr_mode_of_procurement.is_bidding= 1 THEN pr_rfq.eligibility_check
              WHEN with_postponement.to_date IS NOT NULL THEN with_postponement.to_date
              ELSE pr_rfq.deadline
              END 
                ) as eligibility_check,
              (CASE 
              WHEN pr_mode_of_procurement.is_bidding=1 THEN pr_rfq.opening_of_bids
              WHEN with_postponement.to_date IS NOT NULL THEN with_postponement.to_date
              ELSE pr_rfq.deadline
              END 
              ) as opening_of_bids,
              (CASE 
              WHEN pr_mode_of_procurement.is_bidding= 1 THEN pr_rfq.bid_evaluation
              WHEN with_postponement.to_date IS NOT NULL THEN with_postponement.to_date
              ELSE pr_rfq.deadline
              END 
              ) as bid_evaluation,
                rfq_purchase_orders.purchase_orders,
                poContract.contract_notice_of_awards,
                poContract.contract_signing,
                poContract.notice_to_proceed,
                poContract.invitation_pre_bid_conf,
                poContract.invitation_eligibility_check,
                poContract.invitation_opening_of_bids ,
                poContract.invitation_bid_evaluation,
                 poContract.invitation_post_qual,
                 rfq_iars.rfq_iars,
                 rfq_mfos.mfo_codes
            FROM pr_rfq
            JOIN pr_purchase_request ON pr_rfq.pr_purchase_request_id  = pr_purchase_request.id
            LEFT JOIN office ON pr_rfq.fk_office_id = office.id
            LEFT JOIN divisions ON pr_purchase_request.fk_division_id = divisions.id
            LEFT JOIN pr_mode_of_procurement ON pr_rfq.fk_mode_of_procurement_id = pr_mode_of_procurement.id
            LEFT JOIN (SELECT 
            cte_rfq_purchase_orders.pr_rfq_id,
            GROUP_CONCAT(cte_rfq_purchase_orders.notice_of_award) as contract_notice_of_awards,
            GROUP_CONCAT(cte_rfq_purchase_orders.contract_signing) as contract_signing,
            GROUP_CONCAT(cte_rfq_purchase_orders.notice_to_proceed) as notice_to_proceed,
            GROUP_CONCAT(cte_rfq_purchase_orders.invitation_pre_bid_conf) as invitation_pre_bid_conf,
            GROUP_CONCAT(cte_rfq_purchase_orders.invitation_eligibility_check) as invitation_eligibility_check,
            GROUP_CONCAT(cte_rfq_purchase_orders.invitation_opening_of_bids) as invitation_opening_of_bids,
            GROUP_CONCAT(cte_rfq_purchase_orders.invitation_bid_evaluation) as invitation_bid_evaluation,
            GROUP_CONCAT(cte_rfq_purchase_orders.invitation_post_qual) as invitation_post_qual
            
            
             FROM cte_rfq_purchase_orders
            GROUP BY cte_rfq_purchase_orders.pr_rfq_id)as poContract ON pr_rfq.id = poContract.pr_rfq_id
            
            LEFT JOIN (SELECT notice_of_postponement_items.*,notice_of_postponement.to_date FROM `notice_of_postponement_items`
            JOIN notice_of_postponement ON notice_of_postponement_items.fk_notice_of_postponement_id = notice_of_postponement.id
            WHERE is_deleted = 0
            AND notice_of_postponement.is_final = 1
            ) as with_postponement ON pr_rfq.id = with_postponement.fk_rfq_id
            LEFT JOIN (SELECT 
            cte_rfq_purchase_orders.pr_rfq_id,
            GROUP_CONCAT(CONCAT(cte_rfq_purchase_orders.serial_number,'-',cte_rfq_purchase_orders.registered_name,'-',cte_rfq_purchase_orders.po_date)) as purchase_orders
            FROM cte_rfq_purchase_orders
            GROUP BY cte_rfq_purchase_orders.pr_rfq_id) as rfq_purchase_orders ON pr_rfq.id = rfq_purchase_orders.pr_rfq_id
            
            
            LEFT JOIN (SELECT 
            pr_aoq.pr_rfq_id,
            SUM(COALESCE(pr_purchase_order.mooe_amount,0)) as contract_mooe_amount,
            SUM(COALESCE(pr_purchase_order.co_amount,0)) as contract_co_amount
            FROM pr_purchase_order
            JOIN pr_aoq ON pr_purchase_order.fk_pr_aoq_id = pr_aoq.id
            WHERE 
            pr_purchase_order.is_cancelled = 0
            AND pr_aoq.is_cancelled = 0
            GROUP BY pr_aoq.pr_rfq_id) as contract_amount ON pr_rfq.id = contract_amount.pr_rfq_id
            LEFT JOIN (SELECT 
            cte_rfq_iars.pr_rfq_id,
            GROUP_CONCAT(cte_rfq_iars.iar_number) as rfq_iars
            FROM cte_rfq_iars 
            GROUP BY
            cte_rfq_iars.pr_rfq_id) as rfq_iars ON pr_rfq.id = rfq_iars.pr_rfq_id
            LEFT JOIN (SELECT 
                tbl_rfq_mfos.fk_rfq_id,
                GROUP_CONCAT(mfo_pap_code.`code`) as mfo_codes
                FROM tbl_rfq_mfos
                JOIN mfo_pap_code ON tbl_rfq_mfos.fk_mfo_pap_code_id = mfo_pap_code.id
                WHERE tbl_rfq_mfos.is_deleted = 0
                GROUP BY tbl_rfq_mfos.fk_rfq_id) as rfq_mfos ON pr_rfq.id = rfq_mfos.fk_rfq_id
            WHERE pr_rfq.is_cancelled = 0
            AND office.id = :officeId
            AND pr_rfq.created_at LIKE :yr
            AND pr_rfq.created_at <=:lastDayOfMonth
            ")
            ->bindValue(":officeId", $officeId)
            ->bindValue(":lastDayOfMonth", $lastDayOfMonth)
            ->bindValue(":yr", $period->format('Y') . '%')
            ->queryAll();
    }
}
