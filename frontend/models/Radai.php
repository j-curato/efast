<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "radai".
 *
 * @property int $id
 * @property string $date
 * @property string $reporting_period
 * @property int $fk_book_id
 * @property string $serial_number
 * @property string $created_at
 *
 * @property Books $fkBook
 */
class Radai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'radai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'date', 'reporting_period', 'fk_book_id', 'serial_number'], 'required'],
            [['id', 'fk_book_id'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['reporting_period', 'serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['fk_book_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'reporting_period' => 'Reporting Period',
            'fk_book_id' => ' Book ',
            'serial_number' => 'Serial Number',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkBook]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'fk_book_id']);
    }
}
