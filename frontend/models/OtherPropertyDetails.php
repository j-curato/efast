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
            [['id', 'fk_property_id', 'fk_chart_of_account_id',  'salvage_value_prcnt', 'first_month_depreciation', 'start_month_depreciation', 'depreciation_schedule'], 'required'],
            [['id', 'fk_property_id', 'fk_chart_of_account_id'], 'integer'],
            [['created_at'], 'safe'],
            [['salvage_value_prcnt'], 'integer', 'min' => 5],
            [['depreciation_schedule'], 'integer', 'min' => 1],
            [['first_month_depreciation', 'start_month_depreciation'], 'string', 'max' => 255],
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
            'depreciation_schedule' => 'Depreciation Schedule',
            'fk_chart_of_account_id' => 'PPE Type',
            'salvage_value_prcnt' => 'Salvage Value Percent',
            'first_month_depreciation' => 'First Month Depreciation',
            'start_month_depreciation' => 'Start Month Depreciation',
            'created_at' => 'Created At',
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
}
