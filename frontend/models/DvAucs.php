<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dv_aucs".
 *
 * @property int $id
 * @property int|null $process_ors_id
 * @property int|null $raoud_id
 * @property string|null $dv_number
 * @property string|null $reporting_period
 * @property string|null $tax_withheld
 * @property string|null $other_trust_liability_withheld
 * @property float|null $net_amount_paid
 *
 * @property ProcessOrs $processOrs
 * @property Raouds $raoud
 */
class DvAucs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dv_aucs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['net_amount_paid'], 'number'],
            [['dv_number', 'tax_withheld', 'other_trust_liability_withheld'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',

            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'tax_withheld' => 'Tax Withheld',
            'other_trust_liability_withheld' => 'Other Trust Liability Withheld',
            'net_amount_paid' => 'Net Amount Paid',
        ];
    }

    /**
     * Gets query for [[ProcessOrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::class, ['id' => 'process_ors_id']);
    }

    /**
     * Gets query for [[Raoud]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRaoud()
    {
        return $this->hasOne(Raouds::class, ['id' => 'raoud_id']);
    }
    public function getDvAucsEntries()
    {
        return $this->hasMany(DvAucsEntries::class, ['dv_aucs_id' => 'id']);
    }
    public function getNatureOfTransaction()
    {
        return $this->hasOne(NatureOfTransaction::class, ['id' => 'nature_of_transaction_id']);
    }
    public function getMrdClassification()
    {
        return $this->hasOne(MrdClassification::class, ['id' => 'mrd_classification_id']);
    }
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['dv_aucs_id' => 'id']);
    }
    public function getDvAccountingEntries()
    {
        return $this->hasMany(DvAccountingEntries::class, ['dv_aucs_id' => 'id']);
    }
}
