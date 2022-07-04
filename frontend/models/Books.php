<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $name
 */
class Books extends \yii\db\ActiveRecord
{
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
            [['name', 'type'], 'required'],
            [['name', 'account_number', 'type'], 'string', 'max' => 255],
            [[
                'id',
                'name',
                'account_number',
                'type',
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
            'name' => 'Name',
            'account_number' => 'Account Number',
            'type' => 'Book Type',
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
}
