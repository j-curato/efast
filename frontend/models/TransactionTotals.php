<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction_totals".
 *
 * @property string|null $tracking_number
 * @property string|null $name
 * @property string|null $account_name
 * @property string $particular
 * @property float $gross_amount
 * @property float|null $total_ors
 * @property float|null $total_dv
 */
class TransactionTotals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_totals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['particular', 'gross_amount'], 'required'],
            [['gross_amount', 'total_ors', 'total_dv'], 'number'],
            [['tracking_number', 'name', 'account_name', 'particular'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tracking_number' => 'Tracking Number',
            'r_center_name' => 'Responsibility Center',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'gross_amount' => 'Gross Amount',
            'total_ors' => 'Total Ors',
            'total_dv' => 'Total Dv',
            'created_at' => 'Created At',
            'payroll_number'=>'Payroll Number',
            'id'=>'ID'
        ];
    }
}
