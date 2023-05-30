<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%iar_transmittal_items}}".
 *
 * @property int $id
 * @property int $fk_iar_transmittal_id
 * @property int $fk_iar_id
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property IarTransmittal $fkIarTransmittal
 */
class IarTransmittalItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%iar_transmittal_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_iar_transmittal_id', 'fk_iar_id'], 'required'],
            [['fk_iar_transmittal_id', 'fk_iar_id', 'is_deleted'], 'integer'],
            [['created_at'], 'safe'],
            [['fk_iar_transmittal_id'], 'exist', 'skipOnError' => true, 'targetClass' => IarTransmittal::class, 'targetAttribute' => ['fk_iar_transmittal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_iar_transmittal_id' => 'Fk Iar Transmittal ID',
            'fk_iar_id' => 'Fk Iar ID',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkIarTransmittal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkIarTransmittal()
    {
        return $this->hasOne(IarTransmittal::class, ['id' => 'fk_iar_transmittal_id']);
    }
}
