<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_undisbursed_dvs".
 *
 * @property int $id
 * @property string|null $dv_number
 * @property string|null $reporting_period
 * @property string|null $particular
 * @property string|null $natureOfTxn
 * @property string|null $mrdName
 * @property string|null $payee
 * @property string|null $book_name
 * @property float|null $ttlAmtDisbursed
 * @property float|null $ttlTax
 * @property float|null $grossAmt
 * @property string|null $orsNums
 * @property int|null $is_cancelled
 * @property string|null $txnType
 */
class VwUndisbursedDvs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_undisbursed_dvs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_cancelled'], 'integer'],
            [['particular', 'payee', 'orsNums'], 'string'],
            [['ttlAmtDisbursed', 'ttlTax', 'grossAmt'], 'number'],
            [['dv_number', 'natureOfTxn', 'mrdName', 'book_name', 'txnType'], 'string', 'max' => 255],
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
            'natureOfTxn' => 'Nature Of Txn',
            'mrdName' => 'Mrd Name',
            'payee' => 'Payee',
            'book_name' => 'Book Name',
            'ttlAmtDisbursed' => 'Ttl Amt Disbursed',
            'ttlTax' => 'Ttl Tax',
            'grossAmt' => 'Gross Amt',
            'orsNums' => 'Ors Nums',
            'is_cancelled' => 'Is Cancelled',
            'txnType' => 'Txn Type',
        ];
    }
}
