<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "general_ledger".
 *
 * @property int $id
 * @property string $reporting_period
 * @property string $object_code
 * @property int $book_id
 * @property string $created_at
 */
class GeneralLedger extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'general_ledger';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period', 'object_code', 'book_id'], 'required'],
            [['book_id'], 'integer'],
            [['created_at'], 'safe'],
            [['reporting_period'], 'string', 'max' => 20],
            [['object_code'], 'string', 'max' => 255],
            [[
                'id',
                'reporting_period',
                'object_code',
                'book_id',
                'created_at',
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
            'object_code' => 'Object Code',
            'book_id' => 'Book ID',
            'created_at' => 'Created At',
        ];
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function getGeneralLedger()
    {
        return $this->hasOne(AccountingCodes::class, ['object_code' => 'object_code']);
    }
}
