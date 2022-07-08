<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transmittal_entries".
 *
 * @property int $id
 * @property int|null $cash_disbursement_id
 *
 * @property CashDisbursement $cashDisbursement
 */
class TransmittalEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transmittal_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cash_disbursement_id', 'transmittal_id'], 'integer'],
            [[
                'id',
                'cash_disbursement_id',
                'transmittal_id',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['cash_disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursement::class, 'targetAttribute' => ['cash_disbursement_id' => 'id']],
            [['transmittal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transmittal::class, 'targetAttribute' => ['transmittal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cash_disbursement_id' => 'Cash Disbursement ID',
            'transmital_id' => 'Transmital ID',
        ];
    }

    /**
     * Gets query for [[CashDisbursement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['id' => 'cash_disbursement_id']);
    }
    public function getTransmittal()
    {
        return $this->hasOne(Transmittal::class, ['id' => 'transmittal_id']);
    }
}
