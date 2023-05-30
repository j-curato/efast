<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accics".
 *
 * @property int $id
 * @property string $serial_number
 * @property int $fk_book_id
 * @property string $date_issued
 * @property string $created_at
 *
 * @property Books $fkBook
 * @property AcicsCashItems[] $accicsCashItems
 */
class Acics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_number', 'fk_book_id', 'date_issued'], 'required'],
            [['fk_book_id'], 'integer'],
            [['date_issued', 'created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
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
            'fk_book_id' => 'Book',
            'date_issued' => 'Date Issued',
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
     * Gets query for [[AcicsCashItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcicsCashItems()
    {
        return $this->hasMany(AcicsCashItems::class, ['fk_acic_id' => 'id']);
    }
}
