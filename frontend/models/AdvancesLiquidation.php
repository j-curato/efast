<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "advances_liquidation".
 *
 * @property string|null $check_date
 * @property string|null $check_number
 * @property int|null $is_cancelled
 * @property string|null $dv_number
 * @property string|null $reporting_period
 * @property string|null $fund_source
 * @property string $payee
 * @property string|null $particular
 * @property string $gl_object_code
 * @property string $gl_account_title
 * @property float|null $amount
 * @property string|null $withdrawals
 * @property string|null $vat_nonvat
 * @property string|null $ewt_goods_services
 * @property string|null $report_type
 * @property string $sl_object_code
 * @property string $sl_account_title
 */
class AdvancesLiquidation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advances_liquidation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_cancelled'], 'integer'],
            [['particular'], 'string'],
            [['amount'], 'number'],
            [['check_date', 'reporting_period', 'report_type'], 'string', 'max' => 50],
            [['check_number'], 'string', 'max' => 100],
            [['dv_number', 'payee', 'gl_account_title', 'sl_object_code'], 'string', 'max' => 255],
            [['fund_source', 'sl_account_title'], 'string', 'max' => 500],
            [['gl_object_code'], 'string', 'max' => 30],
            [['withdrawals', 'vat_nonvat', 'ewt_goods_services'], 'string', 'max' => 17],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'check_date' => 'Check Date',
            'check_number' => 'Check Number',
            'is_cancelled' => 'Is Cancelled',
            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'fund_source' => 'Fund Source',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'gl_object_code' => 'Gl Object Code',
            'gl_account_title' => 'Gl Account Title',
            'amount' => 'Amount',
            'withdrawals' => 'Withdrawals',
            'vat_nonvat' => 'Vat Nonvat',
            'ewt_goods_services' => 'Ewt Goods Services',
            'report_type' => 'Report Type',
            'sl_object_code' => 'Sl Object Code',
            'sl_account_title' => 'Sl Account Title',
        ];
    }
}
