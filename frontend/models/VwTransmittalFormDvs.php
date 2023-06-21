<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_transmittal_form_dvs".
 *
 * @property int $id
 * @property string|null $check_or_ada_no
 * @property string|null $ada_number
 * @property string|null $reporting_period
 * @property string|null $payee
 * @property string|null $particular
 * @property string|null $dv_number
 * @property float|null $amtDisbursed
 * @property float|null $taxWitheld
 * @property int|null $is_cancelled
 */
class VwTransmittalFormDvs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_transmittal_form_dvs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_cancelled'], 'integer'],
            [['particular'], 'string'],
            [['amtDisbursed', 'taxWitheld'], 'number'],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['ada_number'], 'string', 'max' => 40],
            [['reporting_period'], 'string', 'max' => 50],
            [['payee', 'dv_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'check_or_ada_no' => 'Check Or Ada No',
            'ada_number' => 'Ada Number',
            'reporting_period' => 'Reporting Period',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'dv_number' => 'Dv Number',
            'amtDisbursed' => 'Amount Disbursed',
            'taxWitheld' => 'Tax Withheld',
            'is_cancelled' => 'Is Cancelled',
        ];
    }
}
