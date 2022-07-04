<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mrd_classification".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 */
class MrdClassification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mrd_classification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string', 'max' => 255],
            [[
                'id',
                'reporting_period',
                'amount',
                'book_id',
                'province',
                'fund_source_type',
                'created_at',
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
        ];
    }
}
