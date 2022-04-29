<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "withholding_and_remittance_summary".
 *
 * @property string $payroll_number
 * @property string|null $ors_number
 * @property string|null $dv_number
 * @property string|null $object_code
 * @property string|null $account_title
 * @property float|null $amount
 */
class WithholdingAndRemittanceSummary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'withholding_and_remittance_summary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payroll_number'], 'required'],
            [['amount'], 'number'],
            [['payroll_number', 'ors_number', 'dv_number', 'object_code', 'account_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payroll_number' => 'Payroll Number',
            'ors_number' => 'Ors Number',
            'dv_number' => 'Dv Number',
            'object_code' => 'Object Code',
            'account_title' => 'Account Title',
            'amount' => 'Amount',
        ];
    }
}
