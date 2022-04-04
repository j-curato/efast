<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_trial_balance".
 *
 * @property int $id
 * @property string $reporting_period
 * @property int $book_id
 * @property string $created_at
 */
class SubTrialBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_trial_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period', 'book_id'], 'required'],
            [['book_id'], 'integer'],
            [['created_at'], 'safe'],
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
            'book_id' => 'Book ID',
            'created_at' => 'Created At',
        ];
    }
    public function getBook()
    {
        return $this->hasOne(Books::class,['id'=>'book_id']);
    }
}
