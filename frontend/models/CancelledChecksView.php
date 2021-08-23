<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cancelled_checks_view".
 *
 * @property string|null $province
 * @property string|null $reporting_period
 * @property string|null $check_date
 * @property string|null $check_number
 * @property int|null $from
 * @property int|null $to
 * @property string|null $payee
 */
class CancelledChecksView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cancelled_checks_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'integer'],
            [['province', 'payee'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 20],
            [['check_date', 'check_number'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'province' => 'Province',
            'reporting_period' => 'Reporting Period',
            'check_date' => 'Check Date',
            'check_number' => 'Check Number',
            'from' => 'From',
            'to' => 'To',
            'payee' => 'Payee',
        ];
    }
}
