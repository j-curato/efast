<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_rfq_item".
 *
 * @property int $id
 * @property int|null $pr_rfq_id
 * @property int|null $pr_purchase_request_item_id
 */
class PrRfqItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_rfq_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pr_rfq_id', 'pr_purchase_request_item_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pr_rfq_id' => 'Pr Rfq ID',
            'pr_purchase_request_item_id' => 'Pr Purchase Request Item ID',
        ];
    }
    public function getPurchaseRequestItem()
    {
        return $this->hasOne(PrPurchaseRequestItem::class, ['id'=> 'pr_purchase_request_item_id']);
    }
}
