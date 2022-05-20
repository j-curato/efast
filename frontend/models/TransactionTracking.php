<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction_tracking".
 *
 * @property int $id
 * @property string|null $tracking_number
 * @property string|null $division
 * @property float $gross_amount
 * @property string|null $transaction_date
 * @property string|null $payee
 * @property string $particular
 * @property string|null $ors_number
 * @property string|null $ors_date
 * @property string|null $created_at
 * @property string|null $dv_number
 * @property string|null $recieved_at
 * @property string|null $in_timestamp
 * @property string|null $out_timestamp
 * @property string|null $check_or_ada_no
 * @property string|null $issuance_date
 * @property string|null $cash_is_cancelled
 */
class TransactionTracking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_tracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'gross_amount', 'particular'], 'required'],
            [['id'], 'integer'],
            [['gross_amount'], 'number'],
            [[
                'ors_created_at', 'recieved_at', 'in_timestamp', 'out_timestamp',

                'cash_in',
                'cash_out'
            ], 'safe'],
            [['tracking_number', 'division', 'payee', 'particular', 'ors_number', 'dv_number'], 'string', 'max' => 255],
            [['transaction_date', 'issuance_date'], 'string', 'max' => 50],
            [['ors_date'], 'string', 'max' => 20],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['cash_is_cancelled'], 'string', 'max' => 9],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tracking_number' => 'Tracking Number',
            'division' => 'Division',
            'gross_amount' => 'Gross Amount',
            'transaction_date' => 'Transaction Date',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'ors_number' => 'Ors Number',
            'ors_date' => 'Ors Date',
            'ors_created_at' => 'Ors Created At',
            'dv_number' => 'Dv Number',
            'recieved_at' => 'Recieved At',
            'in_timestamp' => 'Accounting In',
            'out_timestamp' => 'Accounting Out',
            'check_or_ada_no' => 'Check Number',
            'issuance_date' => 'Check Issuance Date',
            'cash_is_cancelled' => 'Check Good/Cancelled',
            'cash_in' => 'Cashier In',
            'cash_out' => 'Cashier Out',
        ];
    }
}
