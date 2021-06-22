<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detailed_dv_aucs".
 *
 * @property string|null $dv_number
 * @property string|null $reporting_period
 * @property string|null $obligation_number
 * @property string|null $transaction_tracking_number
 * @property string $payee
 * @property string|null $particular
 * @property float|null $total_dv
 * @property float|null $total_vat
 * @property float|null $total_ewt
 * @property float|null $total_compensation
 * @property string $mfo_name
 * @property string $mfo_code
 * @property string $allotment_number
 * @property string $allotment_object_code
 * @property string $allotment_account_title
 * @property string $obligation_object_code
 * @property string $obligation_account_title
 * @property float|null $obligation_amount
 * @property float|null $total_obligation
 * @property float|null $dv_amount
 * @property float|null $dv_vat
 * @property float|null $dv_ewt
 * @property float|null $dv_compensation
 * @property string|null $mode_of_payment
 * @property string|null $check_or_ada_no
 * @property string|null $ada_number
 * @property string|null $issuance_date
 * @property string|null $nature_transaction_name
 * @property string|null $mrd_name
 * @property int|null $is_cancelled
 * @property string $doc_name
 */
class DetailedDvAucs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detailed_dv_aucs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['particular'], 'string'],
            [['total_dv', 'total_vat', 'total_ewt', 'total_compensation', 'obligation_amount', 'total_obligation', 'dv_amount', 'dv_vat', 'dv_ewt', 'dv_compensation'], 'number'],
            [['mfo_name', 'mfo_code', 'allotment_number', 'allotment_object_code', 'allotment_account_title', 'obligation_object_code', 'obligation_account_title', 'doc_name'], 'required'],
            [['is_cancelled'], 'integer'],
            [['dv_number', 'obligation_number', 'transaction_tracking_number', 'payee', 'mfo_name', 'mfo_code', 'allotment_account_title', 'obligation_account_title', 'nature_transaction_name', 'mrd_name', 'doc_name'], 'string', 'max' => 255],
            [['reporting_period', 'allotment_number', 'mode_of_payment', 'issuance_date'], 'string', 'max' => 50],
            [['allotment_object_code', 'obligation_object_code'], 'string', 'max' => 30],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['ada_number'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'obligation_number' => 'Obligation Number',
            'transaction_tracking_number' => 'Transaction Tracking Number',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'total_dv' => 'Total Dv',
            'total_vat' => 'Total Vat',
            'total_ewt' => 'Total Ewt',
            'total_compensation' => 'Total Compensation',
            'mfo_name' => 'Mfo Name',
            'mfo_code' => 'Mfo Code',
            'allotment_number' => 'Allotment Number',
            'allotment_object_code' => 'Allotment Object Code',
            'allotment_account_title' => 'Allotment Account Title',
            'obligation_object_code' => 'Obligation Object Code',
            'obligation_account_title' => 'Obligation Account Title',
            'obligation_amount' => 'Obligation Amount',
            'total_obligation' => 'Total Obligation',
            'dv_amount' => 'Dv Amount',
            'dv_vat' => 'Dv Vat',
            'dv_ewt' => 'Dv Ewt',
            'dv_compensation' => 'Dv Compensation',
            'mode_of_payment' => 'Mode Of Payment',
            'check_or_ada_no' => 'Check Or Ada No',
            'ada_number' => 'Ada Number',
            'issuance_date' => 'Issuance Date',
            'nature_transaction_name' => 'Nature Transaction Name',
            'mrd_name' => 'Mrd Name',
            'is_cancelled' => 'Is Cancelled',
            'doc_name' => 'Doc Name',
        ];
    }
}
