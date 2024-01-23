<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $name
 */
class Books extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'name',
                'type',
                'account_name',
                'fk_bank_id',
                'funding_source_code',
                'lapsing'
            ], 'required'],
            [[
                'name',
                'account_number',
                'type',
                'account_name',
                'lapsing',
                'remarks'
            ], 'string', 'max' => 255],
            [[
                'name',
                'account_number',
                'type',
                'account_name',
                'lapsing',
                'remarks'

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

        ];
    }
    public static function getBooksA()
    {
        return Books::find()->asArray()->all();
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'account_number' => 'Account Number',
            'type' => 'Account Type',
            'account_name' => 'Account Name',
            'fk_bank_id' => 'Bank',
            'funding_source_code' => 'Fund Source Code',
            'lapsing' => 'Lapsing',
            'remarks' => 'Remarks',
        ];
    }
    public function getJevPreparation()
    {
        return $this->hasMany(JevPreparation::class, ['book_id' => 'id']);
    }
    public function getRecordAllotment()
    {
        return $this->hasMany(RecordAllotments::class, ['book_id' => 'id']);
    }
    public static function getBookById($id)
    {

        return self::find()
            ->andWhere(['id' => $id])
            ->asArray()
            ->one();
    }
}
