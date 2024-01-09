<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_transmittal".
 *
 * @property string $transmittal_number
 * @property string|null $date
 * @property string $created_at
 */
class PoTransmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_transmittal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'transmittal_number',
                'date',
                'fk_office_id',
                'fk_approved_by'
            ], 'required'],
            [['date', 'created_at'], 'safe'],
            [[
                'fk_office_id',
                'is_accepted',
                'fk_approved_by',
                'fk_officer_in_charge'
            ], 'integer'],
            [['transmittal_number', 'status'], 'string', 'max' => 255],
            [['transmittal_number'], 'unique'],
            [[
                'transmittal_number',
                'date',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
            'status' => 'Status',
            'fk_office_id' => 'Office',
            'is_accepted' => 'is Accepted',
            'fk_approved_by' => 'Approved By',
            'fk_officer_in_charge' => 'Officer In-Charge',
        ];
    }
    public function getPoTransmittalEntries()
    {
        return $this->hasMany(PoTransmittalEntries::class, ['po_transmittal_number' => 'transmittal_number']);
    }
    public function getPoTransmittalToCoa()
    {
        return $this->hasOne(PoTransmittalToCoaEntries::class, ['fk_po_transmittal_id' => 'id']);
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
