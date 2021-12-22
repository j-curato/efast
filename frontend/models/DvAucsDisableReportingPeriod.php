<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%dv_aucs_disable_reporting_period}}".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string $created_at
 */
class DvAucsDisableReportingPeriod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dv_aucs_disable_reporting_period}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['reporting_period'], 'string', 'max' => 25],
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
            'created_at' => 'Created At',
        ];
    }
}
