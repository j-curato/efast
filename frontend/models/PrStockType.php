<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_stock_type".
 *
 * @property int $id
 * @property string|null $part
 * @property string|null $type
 * @property string $created_at
 */
class PrStockType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_stock_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
            [['created_at'], 'safe'],
            [['part'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'part' => 'Part',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }
}
