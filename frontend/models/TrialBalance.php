<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trial_balance".
 *
 * @property int $id
 * @property string $reporting_period
 * @property int $book_id
 * @property string $entry_type
 */
class TrialBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trial_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period', 'book_id', 'entry_type'], 'required'],
            [['book_id'], 'integer'],
            [['reporting_period'], 'string', 'max' => 20],
            [['entry_type'], 'string', 'max' => 255],
            [[
                'id',
                'reporting_period',
                'book_id',
                'entry_type',

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
            'reporting_period' => 'Reporting Period',
            'book_id' => 'Book ID',
            'entry_type' => 'Entry Type',
        ];
    }
    public function getBook()
    {

        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
}
