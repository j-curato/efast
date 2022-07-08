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
            [['transmittal_number'], 'required'],
            [['date', 'created_at'], 'safe'],
            [['transmittal_number'], 'string', 'max' => 255],
            [['transmittal_number'], 'unique'],
            [[
                'transmittal_number',
                'date',
                'created_at',
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
        ];
    }

    /**
     * Gets query for [[PoTransmittalToCoaEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoTransmittalToCoaEntries()
    {
        return $this->hasMany(PoTransmittalToCoaEntries::className(), ['po_transmittal_to_coa_number' => 'transmittal_number']);
    }
}
