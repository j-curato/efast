<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_position".
 *
 * @property int $id
 * @property string|null $position
 */
class EmployeePosition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position' => 'Position',
        ];
    }
}
