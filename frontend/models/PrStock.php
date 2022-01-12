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
            [['bac_code', 'unit_of_measure_id', 'chart_of_account_id'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [[
                'unit_of_measure_id',
                'chart_of_account_id',
                'stock',
                'description',
                'amount',
            ], 'required'],
            [['stock','stock_number'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock' => 'Stock Title',
            'stock_number' => 'Stock Number',
            'bac_code' => 'Bac Code',
            'unit_of_measure_id' => 'Unit Of Measure ',
            'amount' => 'Amount',
            'chart_of_account_id' => 'Chart Of Account ',
            'created_at' => 'Created At',
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
