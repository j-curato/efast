<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fund_category_and_classification_code".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 */
class FundCategoryAndClassificationCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fund_category_and_classification_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            [['from', 'to'], 'integer'],
            [[
                'id',
                'name',
                'description',
                'from',
                'to',
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
            'name' => 'Name',
            'description' => 'Description',
            'from' => 'From',
            'to' => 'To',
        ];
    }
}
