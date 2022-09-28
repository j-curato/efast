<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ir_transmittal_items}}".
 *
 * @property int|null $fk_ir_transmittal_id
 * @property int $fk_ir_id
 * @property string $created_at
 *
 * @property IrTransmittal $fkIrTransmittal
 */
class IrTransmittalItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ir_transmittal_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_ir_transmittal_id', 'fk_ir_id', 'is_deleted'], 'integer'],
            [['fk_ir_id'], 'required'],
            [['created_at'], 'safe'],
            [['fk_ir_transmittal_id'], 'exist', 'skipOnError' => true, 'targetClass' => IrTransmittal::class, 'targetAttribute' => ['fk_ir_transmittal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fk_ir_transmittal_id' => 'Fk Ir Transmittal ID',
            'fk_ir_id' => 'Fk Ir ID',
            'is_deleted' => 'Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkIrTransmittal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkIrTransmittal()
    {
        return $this->hasOne(IrTransmittal::className(), ['id' => 'fk_ir_transmittal_id']);
    }
}
