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
                //  'agency_id', 
                //  'fk_property_id'
            ], 'required'],
            [[
                'agency_id',
                'fk_accountable_officer_id',
                'fk_recieve_by_jocos_id',
                'fk_issued_by_id',
            ], 'integer'],
            [['date', 'fk_property_id'], 'safe'],
            [[
                'employee_id', 'actual_user',
                'old_par_number',
                'location',
                'accountable_officer',
                'recieve_by_jocos',
                'issued_by',
                'issued_to',
                'remarks'

            ], 'string'],
            [['par_number',], 'string', 'max' => 255],
            [['par_number'], 'unique'],
            [[
                'par_number',
                'id',
                'date',
                'employee_id',
                'agency_id',
                'created_at',
                'actual_user',
                'fk_property_id',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
            'employee_id' => 'Recieved By',
            'agency_id' => ' Agency',
            'actual_user' => 'Actual User',
            'fk_property_id' => 'Property Number',
            'old_par_number' => 'old_par_number',
            'location' => 'location',
            'accountable_officer' => 'accountable_officer',
            'fk_accountable_officer_id' => 'fk_accountable_officer_id',
            'fk_recieve_by_jocos_id' => 'fk_recieve_by_jocos_id',
            'recieve_by_jocos' => 'recieve_by_jocos',
            'issued_by' => 'issued_by',
            'fk_issued_by_id' => 'fk_issued_by_id',
            'issued_to' => 'issued_to',
            'remarks' => 'remarks',
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
