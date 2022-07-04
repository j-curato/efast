<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "general_journal".
 *
 * @property int $id
 * @property int $book_id
 * @property string|null $reporting_period
 */
class GeneralJournal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'general_journal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id'], 'required'],
            [['book_id'], 'integer'],
            [['reporting_period'], 'string', 'max' => 20],
            [[
                'id',
                'book_id',
                'reporting_period',
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
            'book_id' => 'Book ID',
            'reporting_period' => 'Reporting Period',
        ];
    }
    public function getBook()
    {

        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
}
