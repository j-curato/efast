<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_details_entry".
 *
 * @property int $id
 * @property int|null $property_details_id
 * @property string|null $object_code
 * @property string|null $first_month
 * @property string|null $last_month
 * @property float|null $salvage_value
 * @property float|null $monthly_depreciation
 * @property int|null $estimated_useful_life
 */
class PropertyDetailsEntry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_details_entry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_details_id', 'estimated_useful_life'], 'integer'],
            [['salvage_value', 'monthly_depreciation'], 'number'],
            [['object_code'], 'string', 'max' => 50],
            [['first_month', 'last_month'], 'string', 'max' => 20],
            [[
                'id',
                'property_details_id',
                'object_code',
                'first_month',
                'last_month',
                'salvage_value',
                'monthly_depreciation',
                'estimated_useful_life',

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
            'property_details_id' => 'Property Details ID',
            'object_code' => 'Object Code',
            'first_month' => 'First Month',
            'last_month' => 'Last Month',
            'salvage_value' => 'Salvage Value',
            'monthly_depreciation' => 'Monthly Depreciation',
            'estimated_useful_life' => 'Estimated Useful Life',
        ];
    }
}
