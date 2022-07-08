<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ro_alphalist".
 *
 * @property int $id
 * @property string $alphalist_number
 * @property string $created_at
 */
class RoAlphalist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ro_alphalist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'alphalist_number'], 'required'],
            [['id'], 'integer'],
            [['created_at'], 'safe'],
            [['alphalist_number', 'reporting_period'], 'string', 'max' => 255],
            [['alphalist_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'id',
                'alphalist_number',
                'reporting_period',
                'created_at',
                'is_final',

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
            'alphalist_number' => 'Alphalist Number',
            'reporting_period' => 'Reporting Period',
            'created_at' => 'Created At',
        ];
    }
}
