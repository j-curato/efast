<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "procurement_summary".
 *
 * @property string|null $project_title
 * @property string|null $prepared_by
 * @property string|null $pr_created_at
 * @property string|null $pr_number
 * @property string|null $pr_date
 * @property string|null $pr_requested_by
 * @property string|null $pr_approved_by
 * @property string|null $purpose
 * @property string|null $stock_title
 * @property string|null $specification
 * @property string|null $unit_of_measure
 * @property int|null $quantity
 * @property float|null $unit_cost
 * @property string|null $rfq_created_at
 * @property string|null $rfq_number
 * @property string|null $rfq_date
 * @property string|null $rfq_deadline
 * @property string|null $canvasser
 * @property string|null $aoq_created_at
 * @property string|null $aoq_number
 * @property string|null $aoq_date
 * @property string|null $supplier_bid_amount
 * @property string|null $lowest
 * @property string|null $remark
 * @property string|null $payee
 * @property string|null $po_created_at
 * @property string|null $po_number
 * @property string|null $contract_type
 * @property string|null $mode_of_procurement
 */
class ProcurementSummary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'procurement_summary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_title', 'prepared_by', 'pr_requested_by', 'pr_approved_by', 'purpose', 'stock_title', 'specification', 'canvasser', 'remark'], 'string'],
            [['pr_created_at', 'pr_date', 'rfq_created_at', 'rfq_date', 'rfq_deadline', 'aoq_created_at', 'aoq_date', 'po_created_at'], 'safe'],
            [['quantity'], 'integer'],
            [['unit_cost'], 'number'],
            [['pr_number', 'unit_of_measure', 'rfq_number', 'aoq_number', 'payee', 'po_number', 'contract_type', 'mode_of_procurement'], 'string', 'max' => 255],
            [['supplier_bid_amount'], 'string', 'max' => 10],
            [['lowest'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'project_title' => 'Project Title',
            'prepared_by' => 'Prepared By',
            'pr_created_at' => 'Pr Created At',
            'pr_number' => 'Pr Number',
            'pr_date' => 'Pr Date',
            'pr_requested_by' => 'Pr Requested By',
            'pr_approved_by' => 'Pr Approved By',
            'purpose' => 'Purpose',
            'stock_title' => 'Stock Title',
            'specification' => 'Specification',
            'unit_of_measure' => 'Unit Of Measure',
            'quantity' => 'Quantity',
            'unit_cost' => 'Unit Cost',
            'rfq_created_at' => 'Rfq Created At',
            'rfq_number' => 'Rfq Number',
            'rfq_date' => 'Rfq Date',
            'rfq_deadline' => 'Rfq Deadline',
            'canvasser' => 'Canvasser',
            'aoq_created_at' => 'Aoq Created At',
            'aoq_number' => 'Aoq Number',
            'aoq_date' => 'Aoq Date',
            'supplier_bid_amount' => 'Supplier Bid Amount',
            'lowest' => 'Lowest',
            'remark' => 'Remark',
            'payee' => 'Payee',
            'po_created_at' => 'Po Created At',
            'po_number' => 'Po Number',
            'contract_type' => 'Contract Type',
            'mode_of_procurement' => 'Mode Of Procurement',
        ];
    }
}
