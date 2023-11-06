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
                'fk_actual_user',
                'fk_ptr_id',
                'is_current_user'
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
            'fk_received_by' => 'Received By',
            'fk_actual_user' => 'Actual User',
            'fk_property_id' => 'Property Number',
            'fk_issued_by_id' => 'Issued By',
            'remarks' => 'remarks',
            'fk_location_id' => 'Location',
            'fk_office_id' => 'Office',
            'fk_ptr_id' => 'PTR',
            'is_current_user' => 'Current User',
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
        return $this->hasOne(Ptr::class, ['id' => 'fk_ptr_id']);
    }
    public function getLocations()
    {
        return $this->hasOne(Location::class, ['id' => 'fk_location_id']);
    }
    public function getPc()
    {
        return $this->hasOne(PropertyCard::class, ['fk_par_id' => 'id']);
    }
    public static function getEmployeeParsA($employeeId)
    {
        return Yii::$app->db->createCommand("SELECT 
                    CAST(par.id AS CHAR(50)) as id,
                    par.par_number,
                    par.date as par_date,
                    IFNULL(actual_user.employee_name,'') as actual_user,
                    location.location,
                    property.property_number,
                    property.date as acquisition_date,
                    property.acquisition_amount,
                    property.description,
                    property.serial_number,
                    IFNULL(property_articles.article_name,property.article) as article,
                    (CASE
                    WHEN par.is_unserviceable =1 THEN 'UnServiceable'
                    ELSE 'Serviceable' 
                    END ) as is_unserviceable
                FROM par 
                JOIN property ON par.fk_property_id = property.id
                LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
                LEFT JOIN employee_search_view as actual_user ON par.fk_actual_user = actual_user.employee_id
                LEFT JOIN location ON par.fk_location_id = location.id
                WHERE par.fk_received_by = :emp_id
                AND par.is_current_user = 1")
            ->bindValue(':emp_id', $employeeId)
            ->queryAll();
    }
}
