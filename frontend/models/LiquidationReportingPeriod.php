<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquidation_reporting_period".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string|null $province
 * @property int|null $is_locked
 */
class LiquidationReportingPeriod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liquidation_reporting_period';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_locked', 'bank_account_id'], 'integer'],
            [['reporting_period', 'province'], 'string', 'max' => 50],
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
            'province' => 'Province',
            'is_locked' => 'Is Locked',
            'bank_account_id' => 'Bank Account'
        ];
    }
}
