<?php

namespace app\models;

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
            [['property_custodian', 'employee_id', 'fk_office_id'], 'integer'],
            [[
                'property_custodian',
                'employee_id',
                'fk_office_id',
                'f_name',
                'l_name',
                'm_name',
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
                'office',
                'created_at',
                'employee_number',
                'suffix',
                'province',


            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
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
        ];
    }
}
