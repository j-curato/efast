<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dv_for_liquidation_report".
 *
 * @property string|null $payee
 * @property string|null $check_number
 * @property string|null $ada_number
 * @property string|null $particular
 * @property string|null $issuance_date
 * @property float|null $total_disbursed
 */
class DvForLiquidationReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dv_for_liquidation_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['particular'], 'string'],
            [['total_disbursed','liquidated',
            'balance'], 'number'],
            [['payee'], 'string', 'max' => 255],
            [['check_number'], 'string', 'max' => 100],
            [['ada_number'], 'string', 'max' => 40],
            [['issuance_date'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payee' => 'Payee',
            'check_number' => 'Check Number',
            'ada_number' => 'Ada Number',
            'particular' => 'Particular',
            'issuance_date' => 'Issuance Date',
            'total_disbursed' => 'Total Disbursed',
            'liquidated_amount' => 'Liquidated',
            'balance' => 'Balance',
        ];
    }
}
