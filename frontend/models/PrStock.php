<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_stock".
 *
 * @property int $id
 * @property string|null $stock
 * @property int|null $bac_code
 * @property int|null $unit_of_measure_id
 * @property float|null $amount
 * @property int|null $chart_of_account_id
 * @property string $created_at
 */
class PrStock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'unit_of_measure_id', 'chart_of_account_id', 'budget_year',
                'pr_stock_type_id',
                'budget_year'
            ], 'integer'],
            [['amount'], 'number'],
            [['cse_type'], 'string', 'max' => 255],
            [['created_at',  'chart_of_account_id'], 'safe'],
            [[
                'unit_of_measure_id',

                'stock_title',
                'amount',
                'bac_code', 'part', 'type',
                'budget_year'
            ], 'required'],
            [['stock_title', 'part', 'type'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_title' => 'Stock Title',
            'bac_code' => 'Bac Code',
            'unit_of_measure_id' => 'Unit Of Measure ',
            'amount' => 'Amount',
            'chart_of_account_id' => 'Chart Of Account ',
            'part' => 'Part No. ',
            'type' => 'Stock Type ',
            'created_at' => 'Created At',
            'budget_year' => 'Budget Year',
            'cse_type' => 'CSE Type',
            'pr_stock_type_id' => 'Stock Type',
            'budget_year' => 'Budget Year',

        ];
    }
    public function getPrStockSpecification()
    {
        return $this->hasMany(PrStockSpecification::class, ['pr_stock_id' => 'id']);
    }
    public function getUnitOfMeasure()
    {
        return $this->hasOne(UnitOfMeasure::class, ['id' => 'unit_of_measure_id']);
    }
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }
}
