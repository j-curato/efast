<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%purchase_order_transmittal_items}}".
 *
 * @property int $id
 * @property int $fk_purchase_order_transmittal_id
 * @property int $fk_purchase_order_item_id
 *
 * @property PurchaseOrderTransmittal $fkPurchaseOrderTransmittal
 */
class PurchaseOrderTransmittalItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchase_order_transmittal_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_purchase_order_transmittal_id', 'fk_purchase_order_item_id'], 'required'],
            [['fk_purchase_order_transmittal_id', 'fk_purchase_order_item_id'], 'integer'],
            [['fk_purchase_order_transmittal_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrderTransmittal::class, 'targetAttribute' => ['fk_purchase_order_transmittal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_purchase_order_transmittal_id' => ' Purchase Order Transmittal ',
            'fk_purchase_order_item_id' => ' Purchase Order ',
        ];
    }

    /**
     * Gets query for [[FkPurchaseOrderTransmittal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPurchaseOrderTransmittal()
    {
        return $this->hasOne(PurchaseOrderTransmittal::class, ['id' => 'fk_purchase_order_transmittal_id']);
    }
}
