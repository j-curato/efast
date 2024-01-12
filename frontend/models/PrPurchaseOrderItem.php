<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
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
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class,
            GenerateIdBehavior::class
        ];
    }
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
            [['fk_pr_purchase_order_id', 'fk_pr_aoq_entries_id', 'is_lowest', 'is_cancelled'], 'integer'],
            [[
                'id',
                'fk_pr_purchase_order_id',
                'fk_pr_aoq_entries_id',
                'is_lowest',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['fk_pr_purchase_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrPurchaseOrder::class, 'targetAttribute' => ['fk_pr_purchase_order_id' => 'id']],
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
            'is_cancelled' => 'Cancelled',
        ];
    }

    /**
     * Gets query for [[FkPrPurchaseOrder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPrPurchaseOrder()
    {
        return $this->hasOne(PrPurchaseOrder::class, ['id' => 'fk_pr_purchase_order_id']);
    }
}
