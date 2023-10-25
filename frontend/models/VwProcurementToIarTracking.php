<?php

namespace app\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "vw_procurement_to_iar_tracking".
 *
 * @property string|null $office_name
 * @property string|null $division
 * @property string $pr_number
 * @property string|null $purpose
 * @property string|null $stock_name
 * @property string|null $specification
 * @property string|null $pr_is_cancelled
 * @property int|null $quantity
 * @property float|null $unit_cost
 * @property string|null $rfq_number
 * @property string|null $rfq_date
 * @property string|null $rfq_deadline
 * @property string|null $rfq_is_cancelled
 * @property string|null $aoq_number
 * @property string|null $aoq_is_cancelled
 * @property string|null $payee_name
 * @property float|null $bidAmount
 * @property float|null $bidGrossAmount
 * @property string|null $po_number
 * @property string|null $po_is_cancelled
 * @property string|null $rfi_number
 * @property string|null $date
 * @property string|null $inspection_from
 * @property string|null $inspection_to
 * @property int|null $inspected_quantity
 * @property string|null $ir_number
 * @property string|null $iar_number
 */
class VwProcurementToIarTracking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_procurement_to_iar_tracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pr_number'], 'required'],
            [['purpose', 'stock_name', 'specification'], 'string'],
            [['quantity', 'inspected_quantity'], 'integer'],
            [['unit_cost', 'bidAmount', 'bidGrossAmount'], 'number'],
            [['rfq_date', 'rfq_deadline', 'rfi_date', 'pr_date', 'inspection_from', 'inspection_to'], 'safe'],
            [['office_name', 'division', 'pr_number', 'rfq_number', 'aoq_number', 'payee_name', 'po_number', 'rfi_number', 'ir_number', 'iar_number'], 'string', 'max' => 255],
            [['pr_is_cancelled', 'rfq_is_cancelled', 'aoq_is_cancelled', 'po_is_cancelled'], 'string', 'max' => 9],
        ];
    }

    public static function getItems($year)
    {
        $user_data = User::getUserDetails();
        $query  =  VwProcurementToIarTracking::find()->where('pr_date LIKE :yr', ['yr' => $year . '%']);
        if (!Yii::$app->user->can('ro_procurement_admin')) {
            $query->andWhere('office_name = :office', ['office' => $user_data->employee->office->office_name]);
        }
        if (!Yii::$app->user->can('po_procurement_admin')) {
            $query->andWhere('division = :division', ['division' => $user_data->employee->empDivision->division]);
        }
        $command = $query->asArray()->all();
        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'office_name' => 'Office Name',
            'division' => 'Division',
            'pr_number' => 'PR No.',
            'purpose' => 'Purpose',
            'stock_name' => 'Stock Name',
            'specification' => 'Specification',
            'pr_is_cancelled' => 'PR Good/Cancelled',
            'quantity' => 'Quantity',
            'unit_cost' => 'Unit Cost',
            'rfq_number' => 'RFQ No.',
            'rfq_date' => 'RFQ Date',
            'rfq_deadline' => 'RFQ Deadline',
            'rfq_is_cancelled' => 'RFQ  Good/Cancelled',
            'aoq_number' => 'AOQ No.',
            'aoq_is_cancelled' => 'AOQ  Good/Cancelled',
            'payee_name' => 'Payee Name',
            'bidAmount' => 'Bid Amount',
            'bidGrossAmount' => 'Bid Gross Amount',
            'po_number' => 'PO No.',
            'po_is_cancelled' => 'PO  Good/Cancelled',
            'poTransmittalNumber' => 'PO Transmittal No.',
            'poTransmittalDate' => 'PO Transmittal Date',
            'rfi_number' => 'RFI No.',
            'rfi_date' => 'RFI Date',
            'inspection_from' => 'Inspection From',
            'inspection_to' => 'Inspection To',
            'inspected_quantity' => 'Inspected Quantity',
            'ir_number' => 'IR No.',
            'iar_number' => 'IAR No.',
            'pr_date' => 'PR Date',
            'iarTransmittalNumber' => 'IAR Transmittal No.',
            'iarTransmittalDate' => 'IAR Transmittal Date',
        ];
    }
}
