<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jev_reporting_period".
 *
 * @property int $id
 * @property string $reporting_period
 * @property int|null $is_disabled
 */
class JevReportingPeriod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jev_reporting_period';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period'], 'required'],
            [['is_disabled'], 'integer'],
            [['reporting_period'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reporting_period' => 'Reporting Period',
            'is_disabled' => 'Is Disabled',
        ];
    }
}
