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
            [['property_number', 'agency_id', 'fk_property_id'], 'required'],
            [['agency_id'], 'integer'],
            [['date', 'fk_property_id'], 'safe'],
            [['employee_id', 'actual_user'], 'string'],
            [['par_number', 'property_number'], 'string', 'max' => 255],
            [['par_number'], 'unique'],
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
            'actual_user' => 'Actual User',
            'fk_property_id'=>'Property Number'
        ];
    }

    /**
     * Gets query for [[PropertyNumber]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'fk_property_id']);
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
        return $this->hasOne(PropertyCard::class, ['fk_par_id' => 'id']);
    }
    public function getPtr()
    {
        return $this->hasOne(Ptr::class, ['par_number' => 'par_number']);
    }
}
