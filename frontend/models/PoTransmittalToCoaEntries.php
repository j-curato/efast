<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_transmittal_to_coa_entries".
 *
 * @property int $id
 * @property string|null $po_transmittal_number
 * @property string|null $po_transmittal_to_coa_number
 *
 * @property PoTransmittal $poTransmittalNumber
 * @property PoTransmittalToCoa $poTransmittalToCoaNumber
 */
class PoTransmittalToCoaEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_transmittal_to_coa_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['po_transmittal_number', 'po_transmittal_to_coa_number'], 'string', 'max' => 255],
            [[
                'id',
                'po_transmittal_number',
                'po_transmittal_to_coa_number',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['po_transmittal_number'], 'exist', 'skipOnError' => true, 'targetClass' => PoTransmittal::class, 'targetAttribute' => ['po_transmittal_number' => 'transmittal_number']],
            [['po_transmittal_to_coa_number'], 'exist', 'skipOnError' => true, 'targetClass' => PoTransmittalToCoa::class, 'targetAttribute' => ['po_transmittal_to_coa_number' => 'transmittal_number']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'po_transmittal_number' => 'Po Transmittal Number',
            'po_transmittal_to_coa_number' => 'Po Transmittal To Coa Number',
        ];
    }

    /**
     * Gets query for [[PoTransmittalNumber]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoTransmittal()
    {
        return $this->hasOne(PoTransmittal::class, ['transmittal_number' => 'po_transmittal_number']);
    }

    /**
     * Gets query for [[PoTransmittalToCoaNumber]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoTransmittalToCoa()
    {
        return $this->hasOne(PoTransmittalToCoa::class, ['transmittal_number' => 'po_transmittal_to_coa_number']);
    }
}
