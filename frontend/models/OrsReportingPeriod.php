<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ors_reporting_period".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property int|null $disabled
 */
class OrsReportingPeriod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ors_reporting_period';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['disabled'], 'integer'],
            [['reporting_period'], 'string', 'max' => 255],
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
            'disabled' => 'Disabled',
        ];
    }
}
