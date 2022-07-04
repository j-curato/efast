<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fund_source_type".
 *
 * @property int $id
 * @property string $name
 * @property string|null $division
 */
class FundSourceType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fund_source_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['division'], 'string', 'max' => 50],
            [[
                'id',
                'name',
                'division',
               
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
            'division' => 'Division',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\FundSourceTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\FundSourceTypeQuery(get_called_class());
    }
}
