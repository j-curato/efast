<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "depreciation_schedule".
 *
 * @property int $id
 * @property string $reporting_period
 * @property int $fk_book_id
 * @property string $created_at
 */
class DepreciationSchedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'depreciation_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period'], 'required'],
            [['fk_book_id'], 'integer'],
            [['created_at'], 'safe'],
            [['reporting_period'], 'string', 'max' => 255],
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
            'fk_book_id' => 'Book',
            'created_at' => 'Created At',
        ];
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'fk_book_id']);
    }
}
