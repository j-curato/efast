<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "par".
 *
 * @property string|null $par_number
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
            [[
                'fk_received_by',
                'fk_property_id',
                'par_number',
                'date',
                'fk_location_id',
                'is_unserviceable',
                'fk_office_id',
                'fk_issued_by_id',
            ], 'required'],
            [[
                'fk_issued_by_id',
                'fk_location_id',
                'is_unserviceable',
                'fk_office_id',
                'fk_actual_user'
            ], 'integer'],
            [['date'], 'safe'],
            [[
                'remarks'
            ], 'string'],
            [['par_number',], 'string', 'max' => 255],
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
            'date' => 'Date',
            'fk_received_by' => 'Recieved By',
            'fk_actual_user' => 'Actual User',
            'fk_property_id' => 'Property Number',
            'fk_issued_by_id' => 'Issued By',
            'remarks' => 'remarks',
            'fk_location_id' => 'Location',
            'fk_office_id' => 'Office',
            'is_unserviceable' => 'Serviceable/Unserviceable'
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
    public function getRecievedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_received_by']);
    }
    public function getActualUser()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_actual_user']);
    }
    public function getPropertyCard()
    {
        return $this->hasOne(PropertyCard::class, ['fk_par_id' => 'id']);
    }
    public function getPtr()
    {
        return $this->hasOne(Ptr::class, ['par_number' => 'par_number']);
    }
    public function getLocations()
    {
        return $this->hasOne(Location::class, ['id' => 'fk_location_id']);
    }
    public function getPc()
    {
        return $this->hasOne(PropertyCard::class, ['fk_par_id'=> 'id']);
    }
}
