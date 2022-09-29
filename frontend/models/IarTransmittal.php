<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%iar_transmittal}}".
 *
 * @property int $id
 * @property string $serial_number
 * @property string|null $date
 * @property string $created_at
 *
 * @property IarTransmittalItems[] $iarTransmittalItems
 */
class IarTransmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%iar_transmittal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number'], 'required'],
            [['id'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
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
     * Gets query for [[IarTransmittalItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIarTransmittalItems()
    {
        return $this->hasMany(IarTransmittalItems::className(), ['fk_iar_transmittal_id' => 'id']);
    }
}
