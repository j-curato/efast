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
            [['property_custodian'], 'integer'],
            [['employee_id', 'f_name', 'l_name', 'm_name', 'status', 'position'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'f_name' => 'F Name',
            'l_name' => 'L Name',
            'm_name' => 'M Name',
            'status' => 'Status',
            'property_custodian' => 'Property Custodian',
            'position' => 'Position',
        ];
    }
}
