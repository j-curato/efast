<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rci".
 *
 * @property int $id
 * @property string $serial_number
 * @property int $fk_book_id
 * @property string $date
 * @property string $reporting_period
 * @property string $created_at
 *
 * @property Books $fkBook
 * @property RciItems[] $rciItems
 */
class Rci extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rci';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number', 'fk_book_id', 'date', 'reporting_period'], 'required'],
            [['id', 'fk_book_id'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 10],
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
            'serial_number' => 'Serial Number',
            'fk_book_id' => ' Book ',
            'date' => 'Date',
            'reporting_period' => 'Reporting Period',
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

    /**
     * Gets query for [[RciItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRciItems()
    {
        return $this->hasMany(RciItems::class, ['fk_rci_id' => 'id']);
    }
}
