<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "conso_sub_trial_balance".
 *
 * @property int $id
 * @property string $reporting_period
 * @property int $book_type
 */
class ConsoSubTrialBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conso_sub_trial_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period', 'book_type'], 'required'],
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
            'book_type' => 'Book Type',
        ];
    }
}
