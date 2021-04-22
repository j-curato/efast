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
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
