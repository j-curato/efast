<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction_archive".
 *
 * @property string|null $account_name
 * @property string|null $tracking_number
 * @property float|null $gross_amount
 * @property string|null $ors_number
 * @property float|null $total_obligation
 * @property string|null $dv_number
 * @property string|null $check_or_ada_no
 * @property string|null $ada_number
 * @property float|null $amount_disbursed
 * @property float|null $vat_nonvat
 * @property float|null $ewt_goods_services
 * @property float|null $compensation
 * @property float|null $other_trust_liabilities
 */
class TransactionArchive extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_archive';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gross_amount', 'total_obligation', 'amount_disbursed', 'vat_nonvat', 'ewt_goods_services', 'compensation', 'other_trust_liabilities'], 'number'],
            [['account_name', 'tracking_number', 'ors_number', 'dv_number'], 'string', 'max' => 255],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['ada_number'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'account_name' => 'Account Name',
            'tracking_number' => 'Tracking Number',
            'gross_amount' => 'Gross Amount',
            'ors_number' => 'Ors Number',
            'total_obligation' => 'Total Obligation',
            'dv_number' => 'Dv Number',
            'check_or_ada_no' => 'Check Or Ada No',
            'ada_number' => 'Ada Number',
            'amount_disbursed' => 'Amount Disbursed',
            'vat_nonvat' => 'Vat Nonvat',
            'ewt_goods_services' => 'Ewt Goods Services',
            'compensation' => 'Compensation',
            'other_trust_liabilities' => 'Other Trust Liabilities',
        ];
    }
}
