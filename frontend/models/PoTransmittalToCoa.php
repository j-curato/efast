<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_transmittal_to_coa".
 *
 * @property string $transmittal_number
 * @property string|null $date
 * @property string $created_at
 *
 * @property PoTransmittalToCoaEntries[] $poTransmittalToCoaEntries
 */
class PoTransmittalToCoa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_transmittal_to_coa';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transmittal_number', 'date', 'fk_approved_by'], 'required'],
            [['fk_officer_in_charge', 'fk_approved_by'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['transmittal_number'], 'string', 'max' => 255],
            [['transmittal_number'], 'unique'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transmittal_number' => 'Transmittal Number',
            'date' => 'Date',
            'created_at' => 'Created At',
            'fk_approved_by' => 'Approved By',
            'fk_officer_in_charge' => 'Officer in Charge',
        ];
    }

    /**
     * Gets query for [[PoTransmittalToCoaEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoTransmittalToCoaEntries()
    {
        return $this->hasMany(PoTransmittalToCoaEntries::class, ['po_transmittal_to_coa_number' => 'transmittal_number']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }
    public function getOfficerInCharge()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_officer_in_charge']);
    }
}
