<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property".
 *
 * @property string $property_number
 * @property int|null $book_id
 * @property int|null $unit_of_measure_id
 * @property int|null $employee_id
 * @property string|null $iar_number
 * @property string|null $article
 * @property string|null $model
 * @property string|null $serial_number
 * @property int|null $quantity
 * @property float|null $acquisition_amount
 *
 * @property Par $par
 */
class Property extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_number', 'date', 'book_id', 'unit_of_measure_id', 'quantity', 'acquisition_amount', 'employee_id', 'article'], 'required'],
            [['date'], 'string'],
            [['book_id', 'unit_of_measure_id', 'quantity', 'estimated_life'], 'integer'],
            [['acquisition_amount', 'salvage_value'], 'number'],
            [['id'], 'unique'],
            [['employee_id', 'article', 'description', 'object_code'], 'string'],
            [['property_number', 'iar_number', 'model', 'serial_number'], 'string', 'max' => 255],

            [['property_number'], 'unique'],
            [[
                'property_number',
                'id',
                'book_id',
                'unit_of_measure_id',
                'employee_id',
                'iar_number',
                'article',
                'model',
                'serial_number',
                'quantity',
                'date',
                'acquisition_amount',
                'created_at',
                'description',
                'object_code',
                'salvage_value',
                'estimated_life',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'property_number' => 'Property Number',
            'book_id' => 'Book ',
            'unit_of_measure_id' => 'Unit Of Measure ',
            'employee_id' => 'Property Custodian',
            'iar_number' => 'IAR Number',
            'article' => 'Article',
            'description' => 'Description',
            'model' => 'Model',
            'serial_number' => 'Serial Number',
            'quantity' => 'Quantity',
            'acquisition_amount' => 'Acquisition Amount',
            'date' => 'Date',
            'id' => 'ID',
            'object_code' => 'Object Code',
            'salvage_value' => 'Salvage Value',
            'estimated_life' => 'Estimated Useful Life',

        ];
    }

    /**
     * Gets query for [[Par]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPar()
    {
        return $this->hasOne(Par::class, ['property_number' => 'property_number']);
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'employee_id']);
    }

    public function getUnitOfMeasure()
    {
        return $this->hasOne(UnitOfMeasure::class, ['id' => 'unit_of_measure_id']);
    }
}
