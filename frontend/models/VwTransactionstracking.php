<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_transactionstracking".
 *
 * @property string|null $transactionNum
 * @property string|null $transactionDate
 * @property string|null $responsibilityCenter
 * @property string|null $payee
 * @property string|null $orsNum
 * @property string|null $dv_number
 * @property string|null $checkNum
 * @property string|null $adaNum
 * @property int|null $cashIsCancelled
 * @property string|null $acicNum
 * @property string|null $acicInBankNum
 * @property string|null $acicInBankDate
 * @property string $dvStatus
 */
class VwTransactionstracking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_transactionstracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cashIsCancelled'], 'integer'],
            [['acicInBankDate'], 'safe'],
            [['transactionNum', 'responsibilityCenter', 'payee', 'orsNum', 'dv_number', 'acicNum', 'acicInBankNum'], 'string', 'max' => 255],
            [['transactionDate'], 'string', 'max' => 50],
            [['checkNum'], 'string', 'max' => 100],
            [['adaNum'], 'string', 'max' => 40],
            [['dvStatus'], 'string', 'max' => 17],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transactionNum' => 'Transaction Num',
            'transactionDate' => 'Transaction Date',
            'responsibilityCenter' => 'Responsibility Center',
            'payee' => 'Payee',
            'orsNum' => 'Ors Num',
            'dv_number' => 'Dv Number',
            'checkNum' => 'Check Num',
            'adaNum' => 'Ada Num',
            'cashIsCancelled' => 'Cash Is Cancelled',
            'acicNum' => 'Acic Num',
            'acicInBankNum' => 'Acic In Bank Num',
            'acicInBankDate' => 'Acic In Bank Date',
            'dvStatus' => 'Dv Status',
        ];
    }
}
