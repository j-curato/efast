<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%employee_search_view}}".
 *
 * @property int $employee_id
 * @property string|null $employee_name
 * @property string|null $position
 */
class EmployeeSearchView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%employee_search_view}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id'], 'integer'],
            [['employee_name'], 'string'],
            [['position'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'employee_name' => 'Employee Name',
            'position' => 'Position',
        ];
    }
}
