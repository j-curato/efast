<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transmittal".
 *
 * @property int $id
 * @property int|null $cash_disbursement_id
 * @property string|null $transmittal_number
 * @property string|null $location
 *
 * @property CashDisbursement $cashDisbursement
 */
class Transmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transmittal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transmittal_number'], 'string', 'max' => 100],
            [['location','date'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transmittal_number' => 'Transmittal Number',
            'location' => 'Location',
            'date' => 'Date',
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
    public function getTransmittalEntries()
    {
        return $this->hasMany(TransmittalEntries::class, ['transmittal_id' => 'id']);
    }
}
