<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "unpaid_obligation".
 *
 * @property string|null $reporting_period
 * @property string|null $serial_number
 * @property float|null $total_amount
 * @property float|null $total_amount_disbursed
 * @property float $unpaid_obligation
 * @property string|null $dv_number
 * @property string|null $check_number
 * @property int|null $is_cancelled
 * @property float|null $amount_disbursed
 * @property float|null $vat_nonvat
 * @property float|null $ewt_goods_services
 * @property float|null $compensation
 * @property float|null $other_trust_liabilities
 * @property float|null $total_withheld
 */
class UnpaidObligation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unpaid_obligation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_amount', 'total_amount_disbursed', 'unpaid_obligation', 'amount_disbursed', 'vat_nonvat', 'ewt_goods_services', 'compensation', 'other_trust_liabilities', 'total_withheld'], 'number'],
            [['is_cancelled'], 'integer'],
            [['reporting_period', 'serial_number', 'dv_number'], 'string', 'max' => 255],
            [['check_number'], 'string', 'max' => 100],
            [[
                'reporting_period',
                'serial_number',
                'total_amount',
                'total_amount_disbursed',
                'unpaid_obligation',
                'dv_number',
                'check_number',
                'is_cancelled',
                'amount_disbursed',
                'vat_nonvat',
                'ewt_goods_services',
                'compensation',
                'other_trust_liabilities',
                'total_withheld',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reporting_period' => 'Reporting Period',
            'serial_number' => 'Serial Number',
            'total_amount' => 'Total Amount',
            'total_amount_disbursed' => 'Total Amount Disbursed',
            'unpaid_obligation' => 'Unpaid Obligation',
            'dv_number' => 'Dv Number',
            'check_number' => 'Check Number',
            'is_cancelled' => 'Is Cancelled',
            'amount_disbursed' => 'Amount Disbursed',
            'vat_nonvat' => 'Vat Nonvat',
            'ewt_goods_services' => 'Ewt Goods Services',
            'compensation' => 'Compensation',
            'other_trust_liabilities' => 'Other Trust Liabilities',
            'total_withheld' => 'Total Withheld',
        ];
    }
}
