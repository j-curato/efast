<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jev_beginning_balance".
 *
 * @property int $id
 * @property int|null $year
 * @property string|null $object_code
 * @property float|null $amount
 * @property string|null $book_id
 */
class JevBeginningBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jev_beginning_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year','book_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'object_code' => 'Object Code',
            'amount' => 'Amount',
            'book_id' => 'Book ID',
        ];
    }
}
