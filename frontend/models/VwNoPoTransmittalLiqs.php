<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_no_po_transmittal_liqs".
 *
 * @property int $id
 * @property string|null $check_date
 * @property string|null $check_number
 * @property string|null $dv_number
 * @property string|null $reporting_period
 * @property string|null $payee
 * @property string|null $particular
 * @property string|null $account_name
 * @property float|null $total_withdrawal
 * @property float|null $total_expanded
 * @property float|null $total_liquidation_damage
 * @property float|null $total_vat
 * @property float|null $gross_payment
 * @property string|null $province
 */
class VwNoPoTransmittalLiqs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_no_po_transmittal_liqs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['particular'], 'string'],
            [['total_withdrawal', 'total_expanded', 'total_liquidation_damage', 'total_vat', 'gross_payment'], 'number'],
            [['check_date', 'check_number'], 'string', 'max' => 50],
            [['dv_number'], 'string', 'max' => 100],
            [['reporting_period'], 'string', 'max' => 20],
            [['payee', 'province'], 'string', 'max' => 255],
            [['account_name'], 'string', 'max' => 511],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'check_date' => 'Check Date',
            'check_number' => 'Check Number',
            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'account_name' => 'Account Name',
            'total_withdrawal' => 'Total Withdrawal',
            'total_expanded' => 'Total Expanded',
            'total_liquidation_damage' => 'Total Liquidation Damage',
            'total_vat' => 'Total Vat',
            'gross_payment' => 'Gross Payment',
            'province' => 'Province',
        ];
    }
}
