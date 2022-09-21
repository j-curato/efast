<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%purchase_order_transmittal}}".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $created_at
 *
 * @property PurchaseOrderTransmittalItems[] $purchaseOrderTransmittalItems
 */
class PurchaseOrderTransmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchase_order_transmittal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number'], 'required'],
            [['id'], 'integer'],
            [['created_at'], 'safe'],
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
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[PurchaseOrderTransmittalItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderTransmittalItems()
    {
        return $this->hasMany(PurchaseOrderTransmittalItems::class, ['fk_purchase_order_transmittal_id' => 'id']);
    }
}
