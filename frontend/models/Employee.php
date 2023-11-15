<?php

namespace app\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property string|null $employee_id
 * @property string|null $f_name
 * @property string|null $l_name
 * @property string|null $m_name
 * @property string|null $status
 * @property int|null $property_custodian
 * @property string|null $position
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_custodian', 'employee_id', 'fk_office_id', 'fk_division_id'], 'integer'],
            [[
                'property_custodian',
                'employee_id',
                'fk_office_id',
                'f_name',
                'l_name',
                'm_name',
                'fk_division_id'
            ], 'required'],
            [['employee_number', 'f_name', 'l_name', 'm_name', 'status', 'position', 'suffix', 'province'], 'string', 'max' => 255],
            [[
                'employee_id',
                'f_name',
                'l_name',
                'm_name',
                'status',
                'property_custodian',
                'position',
                'created_at',
                'employee_number',
                'suffix',
                'province',


            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }
    public function getEmployeeDetails()
    {

        return Employee::find()
            ->select([
                "CONCAT(f_name,' ',
            (CASE
            WHEN m_name !='' THEN CONCAT(LEFT(m_name,1),'. ')
            ELSE ''
            END),
            l_name,
            (CASE
            WHEN employee.suffix !='' THEN CONCAT(', ',employee.suffix)
            ELSE ''
            END)
            ) as `fullName`",
                "employee.position",
                "employee.property_custodian",
                "office.office_name",
                "divisions.division",
                'employee_id'
            ])
            ->joinWith('office')
            ->joinWith('empDivision')
            ->where('employee.employee_id = :id', ['id' => $this->employee_id])
            ->createCommand()->queryOne();
    }
    public static function getEmployeeById($id)
    {

        return Yii::$app->db->createCommand("SELECT employee_name,
        employee_id,
        position
         FROM employee_search_view WHERE employee_id = :id")
            ->bindValue(':id', $id)
            ->queryOne();
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'f_name' => 'First Name',
            'l_name' => 'Last Name',
            'm_name' => 'Middle Name',
            'status' => 'Status',
            'property_custodian' => 'Property Custodian',
            'position' => 'Designation',
            'employee_number' => 'Employee Number',
            'suffix' => 'Suffix',
            'province' => 'Province',
            'fk_office_id' => 'Office/Province',
            'fk_division_id' => 'Division',
        ];
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function getEmpDivision()
    {
        return $this->hasOne(Divisions::class, ['id' => 'fk_division_id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::class, ['fk_employee_id' => 'employee_id']);
    }
    // public function getUser()
    // {
    //     return $this->hasOne(User::class, ['fk_employee_id' => 'employee_id']);
    // }
}
