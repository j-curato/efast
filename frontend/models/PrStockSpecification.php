<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_stock_specification".
 *
 * @property int $id
 * @property int|null $pr_stock_id
 * @property string|null $description
 */
class PrStockSpecification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_stock_specification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pr_stock_id'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pr_stock_id' => 'Pr Stock ID',
            'description' => 'Description',
        ];
    }
}
