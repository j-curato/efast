<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_transmittal_entries".
 *
 * @property int $id
 * @property string|null $po_transmittal_number
 * @property int|null $liquidation_id
 *
 * @property Liquidation $liquidation
 * @property PoTransmittal $poTransmittalNumber
 */
class PoTransmittalEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_transmittal_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['liquidation_id', 'is_deleted', 'is_returned'], 'integer'],
            [['po_transmittal_number'], 'string', 'max' => 255],
            [[
                'po_transmittal_number',
                'status',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['liquidation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Liquidation::class, 'targetAttribute' => ['liquidation_id' => 'id']],
            [['po_transmittal_number'], 'exist', 'skipOnError' => true, 'targetClass' => PoTransmittal::class, 'targetAttribute' => ['po_transmittal_number' => 'transmittal_number']],
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
            'liquidation_id' => 'Liquidation ID',
            'is_deleted' => 'is Deleted',
            'is_returned' => 'is Returned',
        ];
    }

    /**
     * Gets query for [[Liquidation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiquidation()
    {
        return $this->hasOne(Liquidation::class, ['id' => 'liquidation_id']);
    }

    /**
     * Gets query for [[PoTransmittal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoTransmittal()
    {
        return $this->hasOne(PoTransmittal::class, ['transmittal_number' => 'po_transmittal_number']);
    }
}
