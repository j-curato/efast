<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ir_transmittal}}".
 *
 * @property int $id
 * @property string $date
 * @property string $created_at
 *
 * @property IrTransmittalItems[] $irTransmittalItems
 */
class IrTransmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ir_transmittal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'date'], 'required'],
            [['id'], 'integer'],
            [['serial_number'], 'string'],
            [['date', 'created_at'], 'safe'],
            [['id', 'serial_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'date' => 'Date',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[IrTransmittalItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIrTransmittalItems()
    {
        return $this->hasMany(IrTransmittalItems::class, ['fk_ir_transmittal_id' => 'id']);
    }
}
