<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "pr_purchase_request_item".
 *
 * @property int $id
 * @property int|null $pr_purchase_request_id
 * @property int|null $pr_stock_id
 * @property int|null $quantity
 * @property float|null $unit_cost
 * @property string $created_at
 */
class PrPurchaseRequestItem extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'historyLogs' => [
                'class' => HistoryLogsBehavior::class,
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_purchase_request_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'pr_purchase_request_id',
                'pr_stock_id',
                'quantity',
                'unit_of_measure_id',
                'fk_ppmp_cse_item_id',
                'fk_ppmp_non_cse_item_id'
            ], 'integer'],
            [['unit_cost'], 'number'],
            [['specification'], 'string'],
            [['created_at'], 'safe'],
            [[
                'id',
                'pr_purchase_request_id',
                'pr_stock_id',
                'quantity',
                'unit_cost',
                'specification',
                'unit_of_measure_id',
                'created_at',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pr_purchase_request_id' => 'Pr Purchase  Request ',
            'pr_stock_id' => 'Pr Stock ID',
            'quantity' => 'Quantity',
            'unit_cost' => 'Unit Cost',
            'created_at' => 'Created At',
            'specification' => 'Specification',
            'unit_of_measure_id' => 'Unit of Measure',
            'fk_ppmp_cse_item_id' => 'fk_ppmp_cse_item_id',
            'fk_ppmp_non_cse_item_id' => 'fk_ppmp_non_cse_item_id'
        ];
    }
    public function getStock()
    {
        return $this->hasOne(PrStock::class, ['id' => 'pr_stock_id']);
    }
    public function getUnitOfMeasure()
    {
        return $this->hasOne(UnitOfMeasure::class, ['id' => 'unit_of_measure_id']);
    }
}
