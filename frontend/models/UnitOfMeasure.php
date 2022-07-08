<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "unit_of_measure".
 *
 * @property int $id
 * @property string|null $unit_of_measure
 */
class UnitOfMeasure extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unit_of_measure';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_of_measure'], 'string', 'max' => 255],
            [[
                'id',
                'unit_of_measure',

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
            'unit_of_measure' => 'Unit Of Measure',
        ];
    }
}
