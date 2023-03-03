<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%other_property_details}}".
 *
 * @property int $id
 * @property int $fk_property_id
 * @property int $depreciation_schedule
 * @property int $fk_chart_of_account_id
 * @property int $estimated_useful_life
 * @property int $salvage_value_prcnt
 * @property string $first_month_depreciation
 * @property string $start_month_depreciation
 * @property string $created_at
 *
 * @property OtherPropertyDetailItems[] $otherPropertyDetailItems
 */
class OtherPropertyDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%other_property_details}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'fk_property_id',
                'fk_chart_of_account_id',
                'salvage_value_prcnt',
                'useful_life'
            ], 'required'],
            [[
                'id',
                'fk_property_id',
                'fk_chart_of_account_id',
                'salvage_value_prcnt',
                'useful_life'
            ], 'integer'],
            [['created_at'], 'safe'],
            [['salvage_value_prcnt'], 'integer', 'min' => 5],
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
            'fk_property_id' => ' Property Number',
            'fk_chart_of_account_id' => 'PPE Type',
            'salvage_value_prcnt' => 'Salvage Value Percent',
            'created_at' => 'Created At',
            'useful_life' => 'Useful Life in Months'
        ];
    }

    /**
     * Gets query for [[OtherPropertyDetailItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOtherPropertyDetailItems()
    {
        return $this->hasMany(OtherPropertyDetailItems::class, ['fk_other_property_details_id' => 'id']);
    }
    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'fk_property_id']);
    }
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'fk_chart_of_account_id']);
    }
}
