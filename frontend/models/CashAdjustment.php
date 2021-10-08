<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cash_adjustment".
 *
 * @property int $id
 * @property int|null $book_id
 * @property string|null $particular
 * @property string|null $date
 * @property float|null $amount
 */
class CashAdjustment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cash_adjustment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id'], 'integer'],
            [['particular'], 'string'],
            [['amount'], 'number'],
            [['date'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 50],
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
            'particular' => 'Particular',
            'date' => 'Date',
            'amount' => 'Amount',
            
            'reporting_period'=>'Reporting Period',
        ];
    }
    public function getBooks()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
}
