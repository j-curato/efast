<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%par_index}}".
 *
 * @property int $id
 * @property string $par_number
 * @property string|null $date
 * @property string|null $property_number
 * @property string|null $actual_user
 * @property string|null $recieved_by
 * @property string|null $unit_of_measure
 * @property string|null $book_name
 */
class ParIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%par_index}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'par_number'], 'required'],
            [['id'], 'integer'],
            [['date'], 'safe'],
            [['actual_user', 'recieved_by'], 'string'],
            [['par_number', 'property_number', 'unit_of_measure', 'book_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'par_number' => 'Par Number',
            'date' => 'Date',
            'property_number' => 'Property Number',
            'actual_user' => 'Actual User',
            'recieved_by' => 'Recieved By',
            'unit_of_measure' => 'Unit Of Measure',
            'book_name' => 'Book Name',
            'article' => 'Article',
            'description' => 'Description',
            'iar_number' => 'IAR Number',
            'acquisition_amount' => 'Acquisition Amount'
        ];
    }
}
