<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pr_stock}}".
 *
 * @property int $id
 * @property string|null $stock_title
 * @property string|null $bac_code
 * @property int|null $unit_of_measure_id
 * @property float|null $amount
 * @property int|null $chart_of_account_id
 * @property string|null $part
 * @property string|null $type
 * @property string $created_at
 * @property int|null $is_final
 */
class PrStock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pr_stock}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'unit_of_measure_id', 'chart_of_account_id', 'is_final'], 'integer'],
            [['stock_title', 'part', 'type'], 'string'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['bac_code'], 'string', 'max' => 255],
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
            'stock_title' => 'Stock Title',
            'bac_code' => 'Bac Code',
            'unit_of_measure_id' => 'Unit Of Measure ID',
            'amount' => 'Amount',
            'chart_of_account_id' => 'Chart Of Account ID',
            'part' => 'Part',
            'type' => 'Type',
            'created_at' => 'Created At',
            'is_final' => 'Is Final',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PrStockQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PrStockQuery(get_called_class());
    }
}
