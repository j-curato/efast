<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alphalist".
 *
 * @property int $id
 * @property string $alphalist_number
 * @property string $check_range
 * @property string $created_at
 */
class Alphalist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alphalist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alphalist_number', 'check_range', 'province'], 'required'],
            [['created_at'], 'safe'],
            [['alphalist_number', 'check_range', 'province'], 'string', 'max' => 255],
            [['alphalist_number'], 'unique'],
            [['status'], 'integer'],
            [[
                'id',
                'alphalist_number',
                'check_range',
                'province',
                'created_at',
                'status',

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
            'check_range' => 'Check Range',
            'created_at' => 'Created At',
            'province' => 'Province',
            'status' => 'Status',
        ];
    }
}
