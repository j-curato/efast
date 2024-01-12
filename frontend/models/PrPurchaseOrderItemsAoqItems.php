<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use Yii;

/**
 * This is the model class for table "{{%pr_purchase_order_items_aoq_items}}".
 *
 * @property int $id
 * @property int $fk_purchase_order_item_id
 * @property int $fk_aoq_entries_id
 */
class PrPurchaseOrderItemsAoqItems extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            GenerateIdBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pr_purchase_order_items_aoq_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_purchase_order_item_id', 'fk_aoq_entries_id'], 'required'],
            [['fk_purchase_order_item_id', 'fk_aoq_entries_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_purchase_order_item_id' => 'Fk Purchase Order Item ID',
            'fk_aoq_entries_id' => 'Fk Aoq Entries ID',
        ];
    }
}
