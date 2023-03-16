<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "other_property_details_index".
 *
 * @property int $id
 * @property string|null $office_name
 * @property string|null $property_number
 * @property string|null $description
 * @property string|null $uacs
 * @property string|null $general_ledger
 * @property string|null $article
 * @property int $salvage_value_prcnt
 * @property int|null $useful_life
 */
class OtherPropertyDetailsIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'other_property_details_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'salvage_value_prcnt'], 'required'],
            [['id', 'salvage_value_prcnt', 'useful_life'], 'integer'],
            [['description', 'article'], 'string'],
            [['office_name', 'property_number', 'general_ledger'], 'string', 'max' => 255],
            [['uacs'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office_name' => 'Office Name',
            'property_number' => 'Property Number',
            'description' => 'Description',
            'uacs' => 'Uacs',
            'general_ledger' => 'General Ledger',
            'article' => 'Article',
            'salvage_value_prcnt' => 'Salvage Value Prcnt',
            'useful_life' => 'Useful Life',
        ];
    }
}
