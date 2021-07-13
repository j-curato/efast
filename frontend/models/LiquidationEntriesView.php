<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquidation_entries_view".
 *
 * @property string $dv_number
 * @property string|null $check_date
 * @property string|null $check_number
 * @property string|null $fund_source
 * @property string|null $particular
 * @property string|null $payee
 * @property string|null $object_code
 * @property string|null $account_title
 * @property float|null $withdrawals
 * @property float|null $vat_nonvat
 * @property float|null $expanded_tax
 * @property float|null $liquidation_damage
 * @property float|null $gross_payment
 * @property string|null $province
 */
class LiquidationEntriesView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liquidation_entries_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fund_source', 'particular', 'payee'], 'string'],
            [['withdrawals', 'vat_nonvat', 'expanded_tax', 'liquidation_damage', 'gross_payment'], 'number'],
            [['dv_number'], 'string', 'max' => 100],
            [['check_date', 'check_number', 'province'], 'string', 'max' => 50],
            [['object_code'], 'string', 'max' => 30],
            [['account_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reporting_period' => 'Reporting Period',

            'dv_number' => 'Dv Number',
            'check_date' => 'Check Date',
            'check_number' => 'Check Number',
            'fund_source' => 'Fund Source',
            'particular' => 'Particular',
            'payee' => 'Payee',
            'object_code' => 'Object Code',
            'account_title' => 'Account Title',
            'withdrawals' => 'Withdrawals',
            'vat_nonvat' => 'Vat Nonvat',
            'expanded_tax' => 'Expanded Tax',
            'liquidation_damage' => 'Liquidation Damage',
            'gross_payment' => 'Gross Payment',
            'province' => 'Province',
        ];
    }
}
