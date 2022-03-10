<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "par".
 *
 * @property string|null $par_number
 * @property string $property_number
 * @property string|null $date
 * @property int|null $employee_id
 *
 * @property Property $propertyNumber
 */
class Par extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'par';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_number', 'agency_id'], 'required'],
            [['agency_id'], 'integer'],
            [['date'], 'safe'],
            [['employee_id', 'actual_user'], 'string'],
            [['par_number', 'property_number'], 'string', 'max' => 255],
            [['par_number'], 'unique'],
            [['property_number'], 'exist', 'skipOnError' => true, 'targetClass' => Property::class, 'targetAttribute' => ['property_number' => 'property_number']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'par_number' => 'Par Number',
            'property_number' => 'Property Number',
            'date' => 'Date',
            'employee_id' => 'Recieved By',
            'agency_id' => ' Agency',
            'actual_user' => 'Actual User'
        ];
    }

    /**
     * Gets query for [[PropertyNumber]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getProperty()
    {
        return $this->hasOne(Property::class, ['property_number' => 'property_number']);
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'employee_id']);
    }
    public function getActualUser()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'actual_user']);
    }
    public function getPropertyCard()
    {
        return $this->hasOne(PropertyCard::class, ['par_number' => 'par_number']);
    }
    public function getPtr()
    {
        return $this->hasOne(Ptr::class, ['par_number' => 'par_number']);
    }
}
