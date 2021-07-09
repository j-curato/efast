<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquidation_view".
 *
 * @property string|null $check_date
 * @property string|null $check_number
 * @property string $dv_number
 * @property string|null $reporting_period
 * @property string|null $payee
 * @property string|null $particular
 * @property float|null $total_withdrawal
 * @property float|null $total_expanded
 * @property float|null $total_liquidation_damage
 * @property float|null $total_vat
 * @property float|null $gross_payment
 * @property string|null $province
 */
class LiquidationView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liquidation_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payee', 'particular'], 'string'],
            [['total_withdrawal', 'total_expanded', 'total_liquidation_damage', 'total_vat', 'gross_payment'], 'number'],
            [['check_date', 'check_number', 'province'], 'string', 'max' => 50],
            [['dv_number'], 'string', 'max' => 100],
            [['reporting_period'], 'string', 'max' => 20],
            [['id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'check_date' => 'Check Date',
            'check_number' => 'Check Number',
            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'total_withdrawal' => 'Total Withdrawal',
            'total_expanded' => 'Total Expanded',
            'total_liquidation_damage' => 'Total Liquidation Damage',
            'total_vat' => 'Total Vat',
            'gross_payment' => 'Gross Payment',
            'province' => 'Province',
        ];
    }
}
