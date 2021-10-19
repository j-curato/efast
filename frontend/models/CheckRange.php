<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "check_range".
 *
 * @property int $id
 * @property int|null $from
 * @property int|null $to
 */
class CheckRange extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'check_range';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'integer'],
            [['from', 'to', 'reporting_period'], 'required'],
            [['province', 'reporting_period'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'province' => 'Province',
            'reporting_period' => 'Reporting Period',
        ];
    }
}
