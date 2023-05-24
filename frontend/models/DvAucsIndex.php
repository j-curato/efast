<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dv_aucs_index".
 *
 * @property int $id
 * @property string|null $dv_number
 * @property string|null $reporting_period
 * @property string|null $particular
 * @property string|null $natureOfTxn
 * @property string|null $mrdName
 * @property string|null $account_name
 * @property string|null $payee
 * @property float|null $ttlAmtDisbursed
 * @property float|null $ttlTax
 * @property float|null $grossAmt
 * @property string|null $orsNums
 * @property int|null $is_cancelled
 * @property string|null $txnType
 */
class DvAucsIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dv_aucs_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_cancelled'], 'integer'],
            [['particular', 'account_name', 'orsNums'], 'string'],
            [['ttlAmtDisbursed', 'ttlTax', 'grossAmt'], 'number'],
            [['dv_number', 'natureOfTxn', 'mrdName', 'payee', 'txnType'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'particular' => 'Particular',
            'natureOfTxn' => 'Nature Of Transaction',
            'mrdName' => 'MRD Classification',
            'payee' => 'Payee',
            'ttlAmtDisbursed' => 'Total Amount DIsbursed',
            'ttlTax' => 'Total Tax',
            'grossAmt' => 'Gross Amount',
            'orsNums' => 'ORS Nos.',
            'is_cancelled' => 'Is Cancelled',
            'txnType' => 'Transaction Type',
        ];
    }
}
