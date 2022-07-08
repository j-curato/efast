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
            [['transmittal_number'], 'required'],
            [['date', 'created_at'], 'safe'],
            [['transmittal_number', 'status'], 'string', 'max' => 255],
            [['transmittal_number'], 'unique'],
            [[

                'transmittal_number',
                'date',
                'created_at',
                'status',
                'edited',

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
        ];
    }
    public function getPoTransmittalEntries()
    {
        return $this->hasMany(PoTransmittalEntries::class, ['po_transmittal_number' => 'transmittal_number']);
    }
}
