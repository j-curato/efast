<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%process_ors_index}}".
 *
 * @property int $id
 * @property string|null $serial_number
 * @property string|null $reporting_period
 * @property string|null $date
 * @property string|null $tracking_number
 * @property string|null $particular
 * @property string|null $r_center
 * @property string|null $payee
 */
class ProcessOrsIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%process_ors_index}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['particular'], 'string'],
            [['serial_number', 'reporting_period', 'tracking_number', 'r_center', 'payee'], 'string', 'max' => 255],
            [['date'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'reporting_period' => 'Reporting Period',
            'date' => 'Date',
            'tracking_number' => 'Tracking Number',
            'particular' => 'Particular',
            'r_center' => 'R Center',
            'payee' => 'Payee',
        ];
    }
}
