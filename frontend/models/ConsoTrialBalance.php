<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "conso_trial_balance".
 *
 * @property int $id
 * @property string $reporting_period
 * @property string $entry_type
 * @property string $type
 */
class ConsoTrialBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conso_trial_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period', 'entry_type', 'type'], 'required'],
            [['reporting_period'], 'string', 'max' => 20],
            [['entry_type', 'type'], 'string', 'max' => 255],
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
            'entry_type' => 'Entry Type',
            'type' => 'Type',
        ];
    }
}
