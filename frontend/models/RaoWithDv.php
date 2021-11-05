<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rao_with_dv".
 *
 * @property int|null $id
 * @property string|null $serial_number
 * @property string|null $tracking_number
 * @property string|null $payee
 * @property string|null $particular
 * @property string|null $allotment_uacs
 * @property string|null $allotment_account_title
 * @property string|null $ors_uacs
 * @property string|null $ors_account_title
 * @property float|null $amount
 * @property string|null $reporting_period
 * @property string|null $date
 * @property int|null $is_cancelled
 * @property string|null $allotment_serial_number
 * @property string|null $mfo_code
 * @property string|null $mfo_name
 * @property string|null $document_name
 * @property string|null $allotment_book
 * @property string|null $ors_book
 * @property string|null $dv_number
 * @property float|null $dv_amount_disburse
 * @property string|null $dv_cancelled
 * @property string|null $cash_cancelled
 */
class RaoWithDv extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rao_with_dv';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_cancelled'], 'integer'],
            [['amount', 'dv_amount_disburse'], 'number'],
            [['serial_number', 'tracking_number', 'payee', 'particular', 'allotment_account_title', 'ors_account_title', 'mfo_code', 'mfo_name', 'document_name', 'allotment_book', 'ors_book', 'dv_number'], 'string', 'max' => 255],
            [['allotment_uacs', 'ors_uacs'], 'string', 'max' => 30],
            [['reporting_period', 'date'], 'string', 'max' => 20],
            [['allotment_serial_number'], 'string', 'max' => 50],
            [['dv_cancelled', 'cash_cancelled'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'tracking_number' => 'Tracking Number',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'allotment_uacs' => 'Allotment Uacs',
            'allotment_account_title' => 'Allotment Account Title',
            'ors_uacs' => 'Ors Uacs',
            'ors_account_title' => 'Ors Account Title',
            'amount' => 'Amount',
            'reporting_period' => 'Reporting Period',
            'date' => 'Date',
            'is_cancelled' => 'Is Cancelled',
            'allotment_serial_number' => 'Allotment Serial Number',
            'mfo_code' => 'Mfo Code',
            'mfo_name' => 'Mfo Name',
            'document_name' => 'Document Name',
            'allotment_book' => 'Allotment Book',
            'ors_book' => 'Ors Book',
            'dv_number' => 'Dv Number',
            'dv_amount_disburse' => 'Dv Amount Disburse',
            'dv_cancelled' => 'Dv Cancelled',
            'cash_cancelled' => 'Cash Cancelled',
        ];
    }
}
