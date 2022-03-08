<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_purchase_order_item".
 *
 * @property int $id
 * @property int|null $fk_pr_purchase_order_id
 * @property int|null $fk_pr_aoq_entries_id
 * @property int|null $is_lowest
 *
 * @property PrPurchaseOrder $fkPrPurchaseOrder
 */
class PrPurchaseOrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_purchase_order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_pr_purchase_order_id', 'fk_pr_aoq_entries_id', 'is_lowest'], 'integer'],
            [['fk_pr_purchase_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrPurchaseOrder::className(), 'targetAttribute' => ['fk_pr_purchase_order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_pr_purchase_order_id' => 'Fk Pr Purchase Order ID',
            'fk_pr_aoq_entries_id' => 'Fk Pr Aoq Entries ID',
            'is_lowest' => 'Is Lowest',
        ];
    }

    /**
     * Gets query for [[FkPrPurchaseOrder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPrPurchaseOrder()
    {
        return $this->hasOne(PrPurchaseOrder::className(), ['id' => 'fk_pr_purchase_order_id']);
    }
}
